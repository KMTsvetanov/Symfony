<?php

namespace App\Tests\integration;

use App\Tests\DatabaseDependantTestCase;

class YahooFinanceApiClientTest extends DatabaseDependantTestCase
{
    /**
     * @test
     * @group integration
     */
    public function the_yahoo_finance_api_client_returns_the_correct_data()
    {
        // Setup

        // New YahooFinanceApiClint

        $yahooFinanceApiClint = self::$kernel->getContainer()->get('yahoo-finance-api-client');

        // Do something

        $response = $yahooFinanceApiClint->fetchStockProfile('INTC', 'US', 'en-US'); // symbol, region, lang

        $stockProfile = json_decode($response['content']);

        // Make assertions

        $this->assertSame('INTC', $stockProfile->symbol);
        $this->assertSame('Intel Corporation', $stockProfile->shortName);
        $this->assertSame('USD', $stockProfile->currency);
        $this->assertSame('NasdaqGS', $stockProfile->exchangeName);
        $this->assertSame('US', $stockProfile->region);
        $this->assertSame('en-US', $stockProfile->lang);
    }
}