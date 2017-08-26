<?php

require_once 'vendor/autoload.php';

use Cache\Cache;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$exchanges = [
    \Exchanges\Poloniex::class,
    \Exchanges\Foxbit::class,
    \Exchanges\MercadoBitcoin::class,
];

$cache = new Cache();

$json = $cache->read('json');

if (!$json) {
  $data = [];

  foreach ($exchanges as $key => $exchange) {
      $exchangeData = (new $exchange())->data();

      $data[$exchangeData->get('name')] = $exchangeData->get('markets');
  }

  $json = json_encode($data);

  $cache->save('json', $json, '30 seconds');
}

echo $json;
