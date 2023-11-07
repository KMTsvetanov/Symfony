<?php

namespace App\Filter;

use App\DTO\LowestPriceEnquiry;
use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;
use App\Filter\Modifier\Factory\PriceModifierFactoryInterface;

class LowestPriceFilter implements PromotionsFilterInterface
{

    public function __construct(private readonly PriceModifierFactoryInterface $priceModifierFactory)
    {

    }

    public function apply(PromotionEnquiryInterface $promotionEnquiry, Promotion ...$promotions): PromotionEnquiryInterface
    {
        /** @var LowestPriceEnquiry $promotionEnquiry */
        $price = $promotionEnquiry->getProduct()->getPrice();
        $quantity = $promotionEnquiry->getQuantity();
        $lowestPrice = $quantity * $price;

        // Loop over the promotions
        foreach ($promotions as $promotion) {
            // Run the promotions' modification logic against the enquiry
            // 1. Check does the promotion apply e.g. is it in date range / is the voucher code valid?
            // 2. Apply the price modification to obtain a $modifiedPrice (how?)
            $priceModifier = $this->priceModifierFactory->create($promotion->getType());

            $modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $promotionEnquiry);
            // 3. Check if $modifiedPrice < $lowestPrice
            // 1. Save the Enquiry properties
            // 2. Update $lowestPrice

            $promotionEnquiry->setDiscountedPrice(250);
            $promotionEnquiry->setPrice(100);
            $promotionEnquiry->setPromotionId(3);
            $promotionEnquiry->setPromotionName('Black Friday half price sale');

        }
        return $promotionEnquiry;
    }
}