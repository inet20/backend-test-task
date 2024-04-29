<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Product extends Constraint
{
    public string $message = 'Coupon is incorrect';
}