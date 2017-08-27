<?php

require_once 'vendor/autoload.php';

use Cache\Cache;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

function generateJSON()
{
    $exchanges = [
        \Exchanges\Poloniex::class,
        \Exchanges\Foxbit::class,
        \Exchanges\MercadoBitcoin::class,
    ];

    $data = collect();

    foreach ($exchanges as $exchange) {
        $exchangeData = (new $exchange())->data();

        $name = $exchangeData->get('name');
        $marketsData = $exchangeData->get('markets');

        $markets = collect();

        foreach ($marketsData as $currency => $price) {
            $markets->push(compact('currency', 'price'));
        }

        $data->push(compact('name', 'markets'));
    }

    return json_encode($data);
}

echo (new Cache())->get('json', function () {
    return generateJSON();
});
