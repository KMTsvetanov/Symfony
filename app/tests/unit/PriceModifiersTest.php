<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\Modifier\DataRangeMultiplier;
use App\Filter\Modifier\EvenItemsMultiplier;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Tests\ServiceTestCase;

class PriceModifiersTest extends ServiceTestCase
{
    /** @test */
    public function DataRangeMultiplier_returns_a_correctly_modified_price(): void
    {
        // Setup
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2022-11-27');

        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(['to' => '2022-11-28', 'from' => '2022-11-25']);
        $promotion->setType('data_range_multiplier');

        $dataRangeModifier = new DataRangeMultiplier();

        // Do something
        $modifiedPrice = $dataRangeModifier->modify(100, 5, $promotion, $enquiry);

        // Make assertions
        $this->assertEquals(250, $modifiedPrice);
    }

    /** @test */
    public function FixedPriceVoucher_returns_a_correctly_modified_price(): void
    {
        // Setup
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setVoucherCode('123QWE');

        $promotion = new Promotion();
        $promotion->setName('Voucher 123QWE');
        $promotion->setAdjustment(100);
        $promotion->setCriteria(['code' => '123QWE']);
        $promotion->setType('fixed_price_voucher');

        $dataRangeModifier = new FixedPriceVoucher();

        // Do something
        $modifiedPrice = $dataRangeModifier->modify(150, 5, $promotion, $enquiry);

        // Make assertions
        $this->assertEquals(500, $modifiedPrice);
    }

    /** @test */
    public function EvenItemsMultiplier_returns_a_correctly_modified_price(): void
    {
        // Setup
        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);

        $promotion = new Promotion();
        $promotion->setName('Buy one get one free');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(['minimum_quantity' => 2]);
        $promotion->setType('even_items_multiplier');

        $dataRangeModifier = new EvenItemsMultiplier();

        // Do something
        $modifiedPrice = $dataRangeModifier->modify(100, 5, $promotion, $enquiry);

        // Make assertions
        // ((100 * 4) * 0.5) + (100 * 1)
        $this->assertEquals(300, $modifiedPrice);
    }
}