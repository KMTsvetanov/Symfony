<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Filter\LowestPriceFilter;
use App\Tests\ServiceTestCase;

class LowestPriceFilterTest extends ServiceTestCase
{
    /** @test */
    public function lowest_price_promotions_filtering_is_applied_correctly(): void
    {
        // Setup
        $product = new Product();
        $product->setPrice(100);

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setProduct($product);
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2022-11-27');
        $enquiry->setVoucherCode('123QWE');
        $lowestPriceFilter = $this->container->get(LowestPriceFilter::class);

        $promotions = $this->promotionsDataProvider();

        // Do something
        $filteredEnquiry = $lowestPriceFilter->apply($enquiry, ...$promotions);

        // Make assertions
        $this->assertSame(100, $filteredEnquiry->getPrice());
        $this->assertSame(250, $filteredEnquiry->getDiscountedPrice());
        $this->assertSame('Black Friday half price sale', $filteredEnquiry->getPromotionName());
    }

    public function promotionsDataProvider(): array
    {
        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale');
        $promotion->setAdjustment(0.5);
        $promotion->setCriteria(['to' => '2022-11-28', 'from' => '2022-11-25']);
        $promotion->setType('data_range_multiplier');

        $promotion2 = new Promotion();
        $promotion2->setName('Voucher 123QWE');
        $promotion2->setAdjustment(100);
        $promotion2->setCriteria(['code' => '123QWE']);
        $promotion2->setType('fixed_price_voucher');

        $promotion3 = new Promotion();
        $promotion3->setName('Buy one get one free');
        $promotion3->setAdjustment(0.5);
        $promotion3->setCriteria(['minimum_quantity' => 2]);
        $promotion3->setType('even_items_multiplier');

        return [$promotion, $promotion2, $promotion3];
    }
}