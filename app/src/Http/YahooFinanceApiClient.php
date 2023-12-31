<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YahooFinanceApiClient implements FinanceApiClientInterface
{
    private const URL = 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v2/get-summary';
    private const X_RAPID_API_HOST = 'apidojo-yahoo-finance-v1.p.rapidapi.com';

    public function __construct(private HttpClientInterface $httpClient, private string $rapidApiKey)
    {
    }

    public function fetchStockProfile($symbol, $region, $lang): JsonResponse
    {
        $response = $this->httpClient->request('GET', self::URL, [
            'query' => [
                'symbol' => $symbol,
                'region' => $region,
//                'lang' => $lang, // We don't use it
            ],
            'headers' => [
                'X-RapidAPI-Host' => self::X_RAPID_API_HOST,
                'X-RapidAPI-Key' => $this->rapidApiKey,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {

            return new JsonResponse('Finance API Client Error ', 400);
        }

        $stockProfile = json_decode($response->getContent())->price;

        $stockProfileAsArray = [
            'symbol' => $stockProfile->symbol,
            'shortName' => $stockProfile->shortName,
            'currency' => $stockProfile->currency,
            'exchangeName' => $stockProfile->exchangeName,
            'region' => 'US',
            'lang' => 'en-US',
            'price' => $stockProfile->regularMarketPrice->fmt, // 37.92
            'previousClose' => $stockProfile->regularMarketPreviousClose->fmt, // 38.77
            'priceChange' => $stockProfile->regularMarketChange->fmt, // -0.85
        ];

        return new JsonResponse($stockProfileAsArray, 200);
    }
}