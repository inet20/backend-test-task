<?php

namespace App\Entity;

enum CouponType: int
{
    case Fixed = 1;
    case Percentage = 2;
}
