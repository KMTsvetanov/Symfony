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
    public function the_refresh_stock_profile_command_creates_new_record_correctly()
    {
        // Setup

        $application = new Application(self::$kernel);

        // Command

        $command = $application->find('app:refresh-stock-profile');

        $commandTester = new CommandTester($command);

        // Set faked return content
        FakeYahooFinanceApiClient::$content = '{"symbol":"INTC","shortName":"Intel Corporation","currency":"USD","exchangeName":"NasdaqGS","region":"US","lang":"en-US","price":"37.92","previousClose":"38.77","priceChange":"-0.85"}';

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
        $this->assertStringContainsString('Intel Corporation has been saved / updated', $commandTester->getDisplay());
    }

    /** @test */
    public function the_refresh_stock_profile_command_updates_existing_record_correctly()
    {
        // Setup

        // An existing Stock record
        $stock = new Stock();
        $stock->setSymbol('INTC');
        $stock->setShortName('Intel Corporation');
        $stock->setCurrency('USD');
        $stock->setExchangeName('Nasdaq');
        $stock->setRegion('US');
        $stock->setLang('en-US');
        $stock->setPrice(100.32);
        $stock->setPreviousClose(200.22);
        $stock->setPriceChange(-100.10);

        $this->entityManager->persist($stock);
        $this->entityManager->flush();

        $stockId = $stock->getId();

        $application = new Application(self::$kernel);

        // Command

        $command = $application->find('app:refresh-stock-profile');

        $commandTester = new CommandTester($command);

        // Set faked return content
        FakeYahooFinanceApiClient::$statusCode = 200;
        FakeYahooFinanceApiClient::setContent([
            'previous_close' => '500.10',
            'price' => '400.05',
            'priceChange' => '100.05'
        ]);

        // Do something

        $commandStatus = $commandTester->execute([
            'symbol' => 'INTC',
            'region' => 'US',
            'lang' => 'en-US'
        ]);

        // Make assertions

        $stockRepository = $this->entityManager->getRepository(Stock::class);

        /** @var Stock $stock */
        $stock = $stockRepository->find($stockId);

        $this->assertEquals(500.10, $stock->getPreviousClose());
        $this->assertEquals(400.05, $stock->getPrice());
        $this->assertEquals(100.05, $stock->getPriceChange());

        $stockRecordCount = $stockRepository->createQueryBuilder('stock')
            ->select('count(stock)')
            ->getQuery()
            ->getSingleScalarResult();

        // Make assertions

        $this->assertEquals(0, $commandStatus);

        // Check on duplicates i.e. 1 record instead of 2
        $this->assertEquals(1, $stockRecordCount);

//        $this->assertStringContainsString('Intel Corporation has been saved / updated', $commandTester->getDisplay());
    }

    /** @test */
    public function non_200_status_code_response_are_handled_correctly()
    {
        // Setup

        $application = new Application(self::$kernel);

        // Command

        $command = $application->find('app:refresh-stock-profile');

        $commandTester = new CommandTester($command);

        // Set faked return content
        FakeYahooFinanceApiClient::$statusCode = 500;

        FakeYahooFinanceApiClient::$content = 'Finance API Client Error';

        // Do something

        $commandStatus = $commandTester->execute([
            'symbol' => 'INTC',
            'region' => 'US',
            'lang' => 'en-US'
        ]);

        $stockRepository = $this->entityManager->getRepository(Stock::class);

        $stockRecordCount = $stockRepository->createQueryBuilder('stock')
            ->select('count(stock)')
            ->getQuery()
            ->getSingleScalarResult();

        // Make assertions

        $this->assertEquals(1, $commandStatus);
        $this->assertEquals(0, $stockRecordCount);

        $this->assertStringContainsString('Finance API Client Error', $commandTester->getDisplay());
    }
}