<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class Merchant
{
    public const SERVICE_CONFIG_STRING = 'service';
    public const METHOD_CONFIG_STRING = 'method';

    private array $availableProcessors;

    public function __construct(
        private readonly array $paymentProcessors,
        private readonly EntityManagerInterface $entityManager,
        private readonly CouponSentinel $couponSentinel,
        private readonly TaxAccountant  $taxAccountant
    ){
        $this->availableProcessors = array_keys($this->paymentProcessors);
    }

    public function isValidPaymentProcessor(string $paymentProcessor): bool
    {
        return in_array($paymentProcessor, $this->availableProcessors);
    }

    public function calculatePrice(Product $product, string $taxNumber, ?Coupon $coupon): int
    {
        $price = $product->getPrice();
        if ($coupon !== null) {
            $price = $this->couponSentinel->applyCoupon($price, $coupon);
        }

        return $price + $this->taxAccountant->getTax($price, $taxNumber);
    }

    public function buy(Product $product, string $taxNumber, string $paymentProcessor, ?Coupon $coupon)
    {
        $price = $this->calculatePrice($product, $taxNumber, $coupon);

        \call_user_func(
            [$this->paymentProcessors[$paymentProcessor][self::SERVICE_CONFIG_STRING], $this->paymentProcessors[$paymentProcessor][self::METHOD_CONFIG_STRING]],
            $price
        );

        if ($coupon !== null) {
            $coupon->setUsed();
            $this->entityManager->flush();
        }
    }
}