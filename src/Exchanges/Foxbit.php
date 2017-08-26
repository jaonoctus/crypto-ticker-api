<?php

namespace Exchanges;

class Foxbit extends Exchange
{
    protected $name = 'FoxBit';

    protected $api = 'https://api.blinktrade.com/api/v1/BRL/ticker?crypto_currency=BTC';

    protected $markets = [
        'BTCBRL' => 'BTCBRL'
    ];

    protected function getMarketPrice($key)
    {
        return $this->price($this->apiData->get('last'));
    }
}
