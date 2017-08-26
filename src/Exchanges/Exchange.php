<?php

namespace Exchanges;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

abstract class Exchange
{
    /**
     * Exchange Name
     * @var string
     */
    protected $name = '';

    /**
     * Exchange Markets (currencies)
     *
     * @var array
     */
    protected $markets = [];

    /**
     * Exchange API URL
     *
     * @var string
     */
    protected $api = '';

    /**
     * @var Collection
     */
    protected $apiData;

    /**
     * @var array
     */
    private $prices = [];

    /**
     * Exchange constructor.
     */
    public function __construct()
    {
        return $this->callAPI()
                    ->getMarketPrices();
    }

    /**
     * @return Collection
     */
    public function data()
    {
        return collect()
            ->put('name', $this->name())
            ->put('markets', $this->prices);
    }

    /**
     * Get Market Price from API Data
     * @return float
     */
    protected function getMarketPrice($key)
    {
        return null;
    }

    /**
     * @param $value
     * @param bool $round
     * @param int $precision
     * @return float
     */
    protected function price($value, $round = true, $precision = 2)
    {
        return (float) (!$round ? $value : round($value, $precision));
    }

    /**
     * @return $this
     */
    private function getMarketPrices()
    {
        foreach ($this->markets as $key => $name) {
            $this->prices[$name] = $this->getMarketPrice($key);
        }

        return $this;
    }

    /**
     * Get data from API URL
     *
     * @return $this
     */
    private function callAPI()
    {
        $client = new Client();

        $response = $client->get($this->api);
        $body = $response->getBody();
        $this->apiData = collect(json_decode($body));

        return $this;
    }

    private function name()
    {
        return strtoupper($this->name);
    }
}
