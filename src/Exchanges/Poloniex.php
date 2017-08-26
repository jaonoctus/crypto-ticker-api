<?php

namespace Exchanges;

class Poloniex extends Exchange
{
    protected $name = 'Poloniex';

    protected $api = 'https://poloniex.com/public?command=returnTicker';

    protected $markets = [
      'USDT_BTC' => 'USDTBTC',
      'USDT_DASH' => 'USDTDASH',
    ];

    protected function getMarketPrice($key)
    {
        return $this->price($this->apiData->get($key)->last);
    }
}
