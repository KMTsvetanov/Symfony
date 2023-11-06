<?php

namespace App\Filter;

use App\DTO\LowestPriceEnquiry;
use App\DTO\PromotionEnquiryInterface;
use App\Entity\Product;
use App\Entity\Promotion;

class LowestPriceFilter implements PromotionsFilterInterface
{

    public function apply(PromotionEnquiryInterface $promotionEnquiry, Promotion ...$promotion): PromotionEnquiryInterface
    {
        /** @var LowestPriceEnquiry $promotionEnquiry */
        $price = $promotionEnquiry->getProduct()->getPrice();
        $quantity = $promotionEnquiry->getQuantity();
        $lowestPrice = $quantity * $price;

        // Loop over the promotions
            // Run the promotions' modification logic against the enquiry
            // 1. Check does the promotion apply e.g. is it in date range / is the voucher code valid?
            // 2. Apply the price modification to obtain a $modifiedPrice (how?)
//        $modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $promotionEnquiry);
            // 3. Check if $modifiedPrice < $lowestPrice
                // 1. Save the Enquiry properties
                // 2. Update $lowestPrice

        $promotionEnquiry->setDiscountedPrice(250);
        $promotionEnquiry->setPrice(100);
        $promotionEnquiry->setPromotionId(3);
        $promotionEnquiry->setPromotionName('Black Friday half price sale');

        return $promotionEnquiry;
    }
}