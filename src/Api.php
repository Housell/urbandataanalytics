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
                $indicators[] = (string)$Indicator;
            }

            if (!empty($indicators)) {
                $query_parameters['indicators'] = '[' . implode(',', $indicators) . ']';
            }
        }

        if (!empty($price_type)) {
            if (!in_array($price_type, ['asking', 'closing'])) {
                throw new Exception('price_type value ' . $price_type . ' is not allowed');
            }

            $query_parameters['price_type'] = $price_type;
        }


        $Asset->validates();

        $response = $this->post($url, $query_parameters, (string)$Asset);

        $this->checkErrors($response);

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
            foreach ($response->indicators as $position => $value) {
                $Valuation->indicators[$Indicators[$position]->indicator] = $value;
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
     * @param string $url
     * @param array $query_parameters
     * @param array $post_data
     * @return stdClass
     * @throws Exception
     */
    private function post($url, $query_parameters = [], $post_data = null)
    {
        return $this->call($url, $query_parameters, $post_data);
    }

    /**
     * @param string $url
     * @param array $query_parameters
     * @param array $post_data
     * @return stdClass
     * @throws Exception
     */
    private function call($url, $query_parameters = [], $post_data = null)
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
                    urlencode($key),
                    urlencode($value)
                ]);
            }

            $this->call_url .= '?' . implode('&', $vars);
        }


        $this->post_data = $post_data;

        $curl = curl_init($this->call_url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (!empty($this->post_data)) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->post_data);
        }

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

    /**
     * <code>
     * {
     *   "error": {
     *     "instance": "api-74d95f7476-mdcps.2022-03-04T11:14:49.012229",
     *     "type": "errors/assets/new-asset-error",
     *     "detail": "Región no soportada.",
     *     "title": "9"
     *   }
     * }
     * </code>
     * <code>
     * {
     *   "error": {
     *     "instance": "api-74d95f7476-28nkg.2022-03-30T14:33:15.322203",
     *     "errors": [
     *       {
     *         "line": 1,
     *         "message": "El atributo 'reference' debe tener una longitud máxima de 20 posiciones."
     *       }
     *     ],
     *     "title": "preprocessed-with-errors",
     *     "warnings": [],
     *     "type": "errors/assets/bad-request",
     *     "detail": "Preprocessed assets with errors"
     *   }
     * }
     * </code>
     * @param stdClass $response
     * @return void
     * @throws Exception
     */
    private function checkErrors(stdClass $response)
    {
        if (!empty($response->error)) {
            $message = '(' . $response->error->type . ') ' . $response->error->instance . ': ' . PHP_EOL;
            $message .= '- ' . $response->error->title . ': ' . $response->error->detail . PHP_EOL;

            if (isset($response->error->errors)) {
                foreach ($response->error->errors as $error) {
                    if (is_string($error)) {
                        $message .= '-- ' . $error . PHP_EOL;
                    }

                    if (isset($error->message)) {
                        $message .= '-- ' . $error->message . (isset($error->line) ? ' in line ' . $error->line : '') . PHP_EOL;
                    }
                }
            }

            throw new Exception($message);
        }
    }

    /**
     * Mode values:
     * - local-only: the result is only searched in the uDA’s database. This mode is the fastest one.
     * - remote-only: The source is the Property Registry Service.
     * - local-first: first, the reference will be searched in the uDA’s database. If it’s not found, it will get the data from the Property Registry Service.
     * - remote-first: first, the reference will be searched in the Property Registry Service. If it’s not found or an error occurs it will get the data from the uDA’s database.
     *
     * @param string $cadastre_reference
     * @param string $mode
     * @return Cadastre
     * @throws Exception
     */
    public function cadastre($cadastre_reference, $mode = null)
    {
        if (empty($cadastre_reference)) {
            throw new Exception('$cadastre_reference is mandatory');
        }

        if (!is_string($cadastre_reference)) {
            throw new Exception('$cadastre_reference must be an string');
        }

        if ($mode && !is_string($mode)) {
            throw new Exception('$mode must be an string');
        }

        if ($mode && !in_array($mode, [
                'local-only',
                'remote-only',
                'local-first',
                'remote-first',
            ])) {
            throw new Exception('Invalid value fot $mode');
        }

        $url = 'https://geo.reds.urbandataanalytics.com/geocoder/api/v1.0/cadastre/' . $cadastre_reference;

        $response = $this->get($url, $mode ? ['mode' => $mode] : null);

        $this->checkErrors($response);

        $Cadastre = new Cadastre();

        foreach ($response as $key => $value) {
            $Cadastre->$key = $value;
        }

        return $Cadastre;
    }

    /**
     * @param string $url
     * @param array $query_parameters
     * @return stdClass
     * @throws Exception
     */
    private function get($url, $query_parameters = [])
    {
        return $this->call($url, $query_parameters);
    }
}
