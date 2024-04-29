<?php

namespace App\Dto;

use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class PurchasePayload extends CalculatePricePayload
{
    #[Assert\NotBlank]
    #[AppAssert\PaymentProcessor]
    private string $paymentProcessor;

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(string $paymentProcessor): void
    {
        $this->paymentProcessor = $paymentProcessor;
    }
}