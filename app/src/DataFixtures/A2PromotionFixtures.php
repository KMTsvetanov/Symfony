<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class A2PromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $promotion = new Promotion();
        $promotion->setName('Black Friday half price sale');
        $promotion->setType('data_range_multiplier');
        $promotion->setAdjustment(0.5);
        $data = array(
            'from' => '2022-11-25',
            'to' => '2022-11-28',
        );
        $promotion->setCriteria($data);
        $manager->persist($promotion);

        $promotion2 = new Promotion();
        $promotion2->setName('Voucher 123QWE');
        $promotion2->setType('fixed_price_voucher');
        $promotion2->setAdjustment(100);
        $data = array(
            'code' => '123QWE',
        );
        $promotion2->setCriteria($data);
        $manager->persist($promotion2);

        $manager->flush();

        $this->addReference('promotion_1', $promotion);
        $this->addReference('promotion_2', $promotion2);
    }
}
