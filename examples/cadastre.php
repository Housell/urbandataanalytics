<?php

$authorization_token = $argv[1] ?? null;
$cadastre_reference = $argv[2] ?? null;

require __DIR__ . '/../vendor/autoload.php';

use UrbanDataAnalytics\Api;

$Api = new Api($authorization_token);

try {
    $Cadastre = $Api->cadastre($cadastre_reference);
    echo $Cadastre . PHP_EOL;
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}