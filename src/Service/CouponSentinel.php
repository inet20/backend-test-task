<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Repository\CouponRepository;
use Doctrine\ORM\EntityManagerInterface;

class CouponSentinel
{
    private CouponRepository $couponRepository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->couponRepository = $entityManager->getRepository(Coupon::class);
    }

    public function isValidCoupon(string $couponCode): bool
    {
        return $this->couponRepository->findByCode($couponCode) !== null;
    }

    public function applyCoupon(int $price, Coupon $coupon)
    {
        return max(0, $coupon->isFixed() ? $price - $coupon->getValue() : $price * (1 - $coupon->getValue() * 0.01));
    }
}