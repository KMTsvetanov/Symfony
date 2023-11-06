<?php

namespace App\Filter\Modifier;

use App\DTO\LowestPriceEnquiry;
use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class EvenItemsMultiplier implements PriceMultiplierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {
        if ($quantity < 2) {
            return $price * $quantity;
        }

        // Get the odd item... if there is one.
        $oddCount = $quantity % 2; // 0 or 1

        $evenCount = $quantity - $oddCount;

        return (($price * $evenCount) * $promotion->getAdjustment()) + ($oddCount * $price);
    }
}