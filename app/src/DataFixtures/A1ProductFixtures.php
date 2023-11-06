<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class A1ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setPrice(100);
        $manager->persist($product);

        $product2 = new Product();
        $product2->setPrice(200);
        $manager->persist($product2);

        $manager->flush();

        $this->addReference('product_1', $product);
        $this->addReference('product_2', $product2);
    }
}
