<?php

namespace App\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class YahooFinanceApiClient
{
    private const URL = 'https://apidojo-yahoo-finance-v1.p.rapidapi.com/stock/v3/get-profile';
    private const X_RAPID_API_HOST = 'apidojo-yahoo-finance-v1.p.rapidapi.com';

    public function __construct(private HttpClientInterface $httpClient, private string $rapidApiKey)
    {
    }

    public function fetchStockProfile($symbol, $region, $lang): array
    {
        $response = $this->httpClient->request('GET', self::URL, [
            'query' => [
                'symbol' => $symbol,
                'region' => $region,
                'lang' => $lang,
            ],
            'headers' => [
                'X-RapidAPI-Host' => self::X_RAPID_API_HOST,
                'X-RapidAPI-Key' => $this->rapidApiKey,
            ],
        ]);

        dd($response->getContent());
    }
}