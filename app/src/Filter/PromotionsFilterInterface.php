<?php

namespace App\Filter;

use App\DTO\PromotionEnquiryInterface;

interface PromotionsFilterInterface
{
    public function apply(PromotionEnquiryInterface $promotionEnquiry): PromotionEnquiryInterface;
}