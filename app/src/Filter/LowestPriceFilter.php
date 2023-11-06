<?php

namespace App\Filter;

use App\DTO\PromotionEnquiryInterface;

class LowestPriceFilter implements PromotionsFilterInterface
{

    public function apply(PromotionEnquiryInterface $promotionEnquiry): PromotionEnquiryInterface
    {
        $promotionEnquiry->setDiscountedPrice(50);
        $promotionEnquiry->setPrice(100);
        $promotionEnquiry->setPromotionId(3);
        $promotionEnquiry->setPromotionName('Black Friday half price sale');

        return $promotionEnquiry;
    }
}