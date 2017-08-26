<?php

require_once 'vendor/autoload.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$exchanges = [
    \Exchanges\Poloniex::class,
    \Exchanges\Foxbit::class,
    \Exchanges\MercadoBitcoin::class,
];

$data = [];

foreach ($exchanges as $key => $exchange) {
    $exchangeData = (new $exchange())->data();

    $data[$exchangeData->get('name')] = $exchangeData->get('markets');
}

echo json_encode($data);
