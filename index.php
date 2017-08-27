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

    $data = [];

    foreach ($exchanges as $key => $exchange) {
        $exchangeData = (new $exchange())->data();

        $markets = $exchangeData->get('markets');
        $name = $exchangeData->get('name');

        $data[] = [
            $name => [
                'name' => $name,
                'markets' => [],
            ],
        ];

        foreach ($markets as $currency => $price) {
            $data[$key][$name]['markets'][] = [
                $currency => [
                    'name' => $currency,
                    'price' => $price,
                ],
            ];
        }
    }

    return json_encode($data);
}

echo (new Cache())->get('json', function () {
    return generateJSON();
});
