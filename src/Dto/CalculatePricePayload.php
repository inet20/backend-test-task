<?php

namespace App\Dto;

use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CalculatePricePayload
{
    #[Assert\NotBlank(message: "Value should not be blank")]
    private int $product;

    #[Assert\NotBlank]
    #[AppAssert\TaxNumber]
    private string $taxNumber;

    #[AppAssert\Coupon]
    private ?string $couponCode = null;

    public function getProduct(): int
    {
        return $this->product;
    }

    public function setProduct(int $product): void
    {
        $this->product = $product;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function setCouponCode(string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }
}