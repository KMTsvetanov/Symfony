<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FakeYahooFinanceApiClient implements FinanceApiClientInterface
{
    public static int $statusCode = 200;

    public static string $content = '';

    public function fetchStockProfile($symbol, $region, $lang): array
    {

        $stockProfileAsArray = [
            'symbol' => 'INTC',
            'shortName' => 'Intel Corporation',
            'currency' => 'USD',
            'exchangeName' => 'NasdaqGS',
            'region' => 'US',
            'lang' => 'en-US',
            'price' => 37.92,
            'previousClose' => 38.77,
            'priceChange' => -0.85,
        ];

        return [
            'statusCode' => self::$statusCode,
            'content' => json_encode($stockProfileAsArray),
        ];
    }
}