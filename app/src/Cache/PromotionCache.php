<?php

namespace App\Cache;

use App\Entity\Product;
use App\Repository\PromotionRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PromotionCache
{
    public function __construct(private readonly CacheInterface $cache, private readonly PromotionRepository $promotionRepository)
    {

    }

    public function findValidForProduct(Product $product, string $requestDate): ?array
    {
        $key = sprintf("valid-for-product-%d", $product->getId());

        return $this->cache->get($key, function (ItemInterface $item) use ($product, $requestDate) {

            // Set the cache item to expire in 60 min (in seconds)
            $item->expiresAfter(30);

            // Cache miss
            var_dump('Cache miss');

            return $this->promotionRepository->findValidForProduct(
                $product,
                date_create_immutable($requestDate)
            );
        });
    }
}