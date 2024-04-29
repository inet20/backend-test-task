<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Coupon extends Constraint
{
    public string $message = 'Coupon is incorrect';
}