<?php

namespace App\Tests\feature;

use App\Entity\Stock;
use App\Http\FakeYahooFinanceApiClient;
use App\Tests\DatabaseDependantTestCase;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RefreshStockProfileCommandTest extends DatabaseDependantTestCase
{
    /** @test */
    public function the_refresh_stock_prole_works_correctly_when_a_stock_record_does_not_exist()
    {
        // Setup

        $application = new Application(self::$kernel);

        // Command

        $command = $application->find('app:refresh-stock-profile');

        $commandTester = new CommandTester($command);

        // Set faked return content
        FakeYahooFinanceApiClient::$content = '{"symbol":"INTC","shortName":"Intel Corporation","currency":"USD","exchangeName":"NasdaqGS","region":"US","lang":"en-US","price":37.92,"previousClose":38.77,"priceChange":-0.85}';

        // Do something

        $commandTester->execute([
            'symbol' => 'INTC',
            'region' => 'US',
            'lang' => 'en-US'
        ]);

        // Make assertions

        $stockRepository = $this->entityManager->getRepository(Stock::class);

        /** @var Stock $stock */
        $stock = $stockRepository->findOneBy([
            'symbol' => 'INTC',
        ]);

        $this->assertEquals('Intel Corporation', $stock->getShortName());
        $this->assertEquals('USD', $stock->getCurrency());
        $this->assertEquals('NasdaqGS', $stock->getExchangeName());
        $this->assertEquals('US', $stock->getRegion());
        $this->assertEquals('en-US', $stock->getLang());
        $this->assertEquals(37.92, $stock->getPrice());
        $this->assertEquals(38.77, $stock->getPreviousClose());
        $this->assertEquals(-0.85, $stock->getPriceChange());
    }
}