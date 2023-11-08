<?php

namespace App\Tests;

use App\Entity\Stock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TddTest extends DatabaseDependantTestCase
{
    /** @test */
    public function is_it_working(): void
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function a_stock_record_can_be_created_in_the_database()
    {
        // Set up

        // Stock
        $stock = new Stock();

        // ** Fields ***

        // symbol
        $stock->setSymbol('INTC');

        // shortName
        $stock->setShortName('Intel Corporation');

        // currency
        $stock->setCurrency('USD');

        // exchangeName
        $stock->setExchangeName('Nasdaq');

        // region
        $stock->setRegion('US');

        // lang
        $stock->setLang('en-US');

        $price = 38.77;
        $previousClose = 37.95;
        $priceChange = $price - $previousClose;

        // price
        $stock->setPrice($price);

        // previousClose
        $stock->setPreviousClose($previousClose);

        // priceChange = price - previousClose
        $stock->setPriceChange($priceChange);

        $this->entityManager->persist($stock);

        // Do something

        $this->entityManager->flush();

        $stockRepository = $this->entityManager->getRepository(Stock::class);

        /** @var Stock $stockRecord */
        $stockRecord = $stockRepository->findOneBy(['symbol' => 'INTC']);

        // Make assertions

        $this->assertEquals('Intel Corporation', $stockRecord->getShortName());
        $this->assertEquals('USD', $stockRecord->getCurrency());
        $this->assertEquals('US', $stockRecord->getRegion());
        $this->assertEquals('en-US', $stockRecord->getLang());
        $this->assertEquals('38.77', $stockRecord->getPrice());
        $this->assertEquals('37.95', $stockRecord->getPreviousClose());
        $this->assertEquals('0.82', $stockRecord->getPriceChange());
    }
}