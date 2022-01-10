<?php

namespace UrbanDataAnalytics;

use Exception;
use stdClass;

class Api
{
    // Configuration
    public $authorization_token = '';

    // Registry
    public $call_url;
    public $post_data;
    public $raw_response;
    public $info;

    /**
     * @param Asset $Asset
     * @param int $portfolio_id
     * @param Indicator[]|null $Indicators
     * @param null $price_type
     * @return Valuation
     * @throws Exception
     */
    public function valuation(Asset $Asset, $portfolio_id, array $Indicators = null, $price_type = null)
    {
        if (empty($portfolio_id)) {
            throw new Exception('Portfolio_id is mandatory');
        }

        $url = 'https://reds.urbandataanalytics.com/assets/api/v1.0/portfolio/' . $portfolio_id . '/asset';
        $query_parameters = [];

        if (!empty($Indicators)) {
            $indicators = [];

            foreach ($Indicators as $position => $Indicator) {
                if (!$Indicator instanceof Indicator) {
                    throw new Exception('Object at position ' . $position . ' is not an Indicator');
                }

                $Indicator->validates();
                $indicator = $this->toJson($Indicator);
                $indicators[] = $indicator;
            }

            if (!empty($indicators)) {
                $query_parameters['indicators'] = $indicators;
            }
        }

        if (!empty($price_type)) {
            if (!in_array($price_type, ['asking', 'closing'])) {
                throw new Exception('price_type value ' . $price_type . ' is not allowed');
            }

            $query_parameters['price_type'] = $price_type;
        }


        $Asset->validates();
        $json = $this->toJson($Asset);

        $response = $this->post($url, $query_parameters, $json);

        if (!empty($response->error)) {
            throw new Exception('Valuation Error: ' . $response->error->detail);
        }

        $Valuation = new Valuation();
        $Valuation->id = $response->id;

        if (!empty($response->competitors)) {
            foreach ($response->competitors as $competitor) {
                $Competitor = new Competitor();

                foreach ($competitor as $key => $value) {
                    $Competitor->$key = $value;
                }

                $Valuation->competitors[] = $Competitor;
            }
        }

        if (!empty($response->indicators)) {
            foreach ($response->indicators as $indicator) {
                $Indicator = new Indicator();

                foreach ($indicator as $key => $value) {
                    $Indicator->$key = $value;
                }

                $Valuation->indicators[] = $Indicator;
            }
        }

        $Asset = new Asset();

        foreach ($response->attributes as $key => $value) {
            $Asset->$key = $value;
        }

        $Valuation->attributes = $Asset;
        $Valuation->best_score = $response->best_score;
        $Valuation->forecast = $response->forecast;

        return $Valuation;
    }

    /**
     * @param object $Class
     * @return string
     * @throws Exception
     */
    public function toJson($Class)
    {
        $json = json_encode(array_filter(get_object_vars($Class), function ($value) {
            return !is_null($value);
        }));

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Exception('Error encoding JSON ' . json_last_error_msg());
        }

        return $json;
    }

    /**
     * @param string $url
     * @param array $query_parameters
     * @param array $post_data
     * @return stdClass
     * @throws Exception
     */
    private function post($url, $query_parameters = [], $post_data = null)
    {
        if (!$url) {
            throw new Exception('Endpoint not set');
        }

        if (!$this->authorization_token) {
            throw new Exception('Api key not set');
        }

        $this->call_url = $url;

        if (!empty($query_parameters)) {
            $vars = [];

            foreach ($query_parameters as $key => $value) {
                $vars[] = implode('=', [
                    $key,
                    $value
                ]);
            }

            $this->call_url .= '?' . urlencode(implode('&', $vars));
        }


        $this->post_data = $post_data;

        $curl = curl_init($this->call_url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->post_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Token ' . $this->authorization_token,
            'Accept: application/json',
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);

        $this->raw_response = curl_exec($curl);
        $this->info = curl_getinfo($curl);
        $error_number = curl_errno($curl);
        curl_close($curl);

        if ($error_number) {
            throw new Exception('cURL error (' . $error_number . '):' . PHP_EOL . curl_strerror($error_number));
        }

        $this->checkHttpResponse();

        $response = json_decode($this->raw_response);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new Exception('Error encoding response JSON ' . json_last_error_msg());
        }

        return $response;
    }

    /**
     * HTTP Responses
     *
     * HTTP status depend on the result of the operation:
     * - 200 OK: the operation was successful. Content in response’s body is expected.
     * - 201 Created: the operation was successful and the resource was created.
     * - 204 No Content: the operation was successful. Content in response’s body is not expected.
     * - 400 Bad Request: some parameters in the request are not valid.
     * - 401 Unauthorized: the token is not valid (see authentication section)
     * - 404 Not Found: the resource doesn’t exist.
     * - 429 Too Many Requests: the quota associated to the resource is exhausted (see quotas section).
     * - 500 Internal Server Error: an unexpected error occurred. We monitor our systems using [Sentry](https://sentry.io/] so there’s a big change that we already received the notification with your error. Anyway, don’t hesitate to contact to support sending the request that produces the error.
     * @throws Exception
     */
    private function checkHttpResponse()
    {
        switch ($this->info['http_code']) {
            case 200:
            case 201:
                break;

            case 204:
                $msg = 'No Content';
                throw new Exception('HTTP Response (' . $this->info['http_code'] . '):' . PHP_EOL . $msg);

            case 400:
                $msg = 'Bad Request';
                throw new Exception('HTTP Response (' . $this->info['http_code'] . '):' . PHP_EOL . $msg);

            case 401:
                $msg = 'Unauthorized';
                throw new Exception('HTTP Response (' . $this->info['http_code'] . '):' . PHP_EOL . $msg);

            case 404:
                $msg = 'Not Found';
                throw new Exception('HTTP Response (' . $this->info['http_code'] . '):' . PHP_EOL . $msg);

            case 429:
                $msg = 'Too Many Requests';
                throw new Exception('HTTP Response (' . $this->info['http_code'] . '):' . PHP_EOL . $msg);

            case 500:
                $msg = 'Internal Server Error';
                throw new Exception('HTTP Response (' . $this->info['http_code'] . '):' . PHP_EOL . $msg);

            default:
                $msg = 'Unexpected HTTP code';
                throw new Exception('HTTP Response (' . $this->info['http_code'] . '):' . PHP_EOL . $msg);
        }
    }
}