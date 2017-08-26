<?php

namespace Exchanges;

class MercadoBitcoin extends Exchange
{
    protected $name = 'MercadoBitcoin';

    protected $api = 'https://www.mercadobitcoin.net/api/BTC/ticker/';

    protected $markets = [
        'BTCBRL' => 'BTCBRL'
    ];

    protected function getMarketPrice($key)
    {
        return $this->price($this->apiData->get('ticker')->last);
    }
}
