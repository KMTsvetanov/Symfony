<?php

namespace App\Tests\feature;

use App\Entity\Stock;
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
        $this->assertEquals('38.77', $stock->getPrice());
        $this->assertEquals('37.95', $stock->getPreviousClose());
        $this->assertEquals('0.82', $stock->getPriceChange());
    }
}