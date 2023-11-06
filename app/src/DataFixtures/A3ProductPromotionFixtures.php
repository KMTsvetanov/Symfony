<?php

namespace App\DataFixtures;

use App\Entity\ProductPromotion;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class A3ProductPromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productPromotion = new ProductPromotion();
        $productPromotion->setProduct($this->getReference('product_1'));
        $productPromotion->setPromotion($this->getReference('promotion_1'));
        $productPromotion->setValidTo(new DateTime('2022-11-28'));
        $manager->persist($productPromotion);

        $productPromotion2 = new ProductPromotion();
        $productPromotion2->setProduct($this->getReference('product_1'));
        $productPromotion2->setPromotion($this->getReference('promotion_2'));
        $productPromotion2->setValidTo(null);
        $manager->persist($productPromotion2);

        $manager->flush();
    }
}
