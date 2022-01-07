<?php

namespace UrbanDataAnalytics;

use Exception;

class Api
{
    // Configuration
    public $authorization_token = '';

    // Registry
    private $call_url;
    private $post_data;
    private $raw_response;
    private $info;

    /**
     * @param Asset $Asset
     * @param int $portfolio_id
     * @param Indicator[]|null $Indicators
     * @param null $price_type
     * @throws Exception
     */
    public function valuation(Asset $Asset, int $portfolio_id, array $Indicators = null, $price_type = null)
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

        $this->post($url, $query_parameters, $json);

        echo $this->call_url . PHP_EOL;
        echo $this->post_data . PHP_EOL;
        echo $this->raw_response . PHP_EOL;
        echo json_encode($this->info) . PHP_EOL;
    }

    /**
     * @param object $Class
     * @return string
     * @throws Exception
     */
    public function toJson(object $Class): string
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
     * @return bool|string
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

        return $this->raw_response;
    }
}