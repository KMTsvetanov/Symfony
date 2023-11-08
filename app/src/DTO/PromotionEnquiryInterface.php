<?php

namespace App\DTO;

use App\Entity\Product;

interface PromotionEnquiryInterface
{
    public function getProduct(): ?Product;

    public function setPromotionId(int $id);
    public function setPromotionName(string $promotionName);
}