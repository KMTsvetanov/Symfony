<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FakeYahooFinanceApiClient implements FinanceApiClientInterface
{
    public static int $statusCode = 200;

    public static string $content = '';

    public static function setContent(array $overrides): void
    {
        self::$content = json_encode(array_merge([
            'symbol' => 'INTC',
            'shortName' => 'Intel Corporation',
            'currency' => 'USD',
            'exchangeName' => 'NasdaqGS',
            'region' => 'US',
            'lang' => 'en-US',
        ], $overrides));
    }

    public function fetchStockProfile($symbol, $region, $lang): JsonResponse
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

        return new JsonResponse(self::$content, self::$statusCode, [], $json = true); // Already json, don't encode
    }
}