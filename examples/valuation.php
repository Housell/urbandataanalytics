<?php

$authorization_token = $argv[1] ?? null;
$portfolio_code = $argv[2] ?? null;

require '../vendor/autoload.php';

use UrbanDataAnalytics\Api;

$Api = new Api();
$Api->authorization_token = $authorization_token;

use UrbanDataAnalytics\Asset;

$Asset = new Asset();
$Asset->operation = Asset::OPERATION_SALE;
$Asset->area = 40;
$Asset->lat = 40.45454062568134;
$Asset->lon = -3.7071921786304336;
$Asset->portfolio_id = $portfolio_code;

use UrbanDataAnalytics\Indicator;

$indicator = new Indicator();
$indicator->indicator = Indicator::AVG_PRICE;
$indicator->admin_level = Indicator::ADMIN_LEVEL_NEIGHBORHOOD;

try {
    $Valuation = $Api->valuation($Asset, $portfolio_code, [$indicator]);
    echo $Valuation . PHP_EOL;
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}