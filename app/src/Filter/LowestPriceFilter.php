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
        $promotionEnquiry->setPrice($price);
        $quantity = $promotionEnquiry->getQuantity();
        $lowestPrice = $quantity * $price;

        foreach ($promotions as $promotion) {

            $priceModifier = $this->priceModifierFactory->create($promotion->getType());

            $modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $promotionEnquiry);

            if ($modifiedPrice < $lowestPrice) {

                $promotionEnquiry->setDiscountedPrice($modifiedPrice);
                $promotionEnquiry->setPromotionId($promotion->getId());
                $promotionEnquiry->setPromotionName($promotion->getName());

                $lowestPrice = $modifiedPrice;
            }

        }

        return $promotionEnquiry;
    }
}