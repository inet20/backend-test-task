<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\CouponType;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadProducts($manager);
        $this->loadCoupons($manager);

        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager): void
    {
        $manager->persist(new Product('Iphone', 100));
        $manager->persist(new Product('Наушники', 20));
        $manager->persist(new Product('Чехол', 10));
    }

    private function loadCoupons(ObjectManager $manager): void
    {
        $manager->persist(new Coupon('F10', CouponType::Fixed,10));
        $manager->persist(new Coupon('F30', CouponType::Fixed,10));
        $manager->persist(new Coupon('P10', CouponType::Percentage, 10));
        $manager->persist(new Coupon('P20', CouponType::Percentage,20));
    }
}
