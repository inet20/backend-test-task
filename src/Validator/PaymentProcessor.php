<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PaymentProcessor extends Constraint
{
    public string $message = 'Unsupported payment processor';
}