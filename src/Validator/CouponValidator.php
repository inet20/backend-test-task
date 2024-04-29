<?php

namespace App\Validator;

use App\Service\CouponSentinel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CouponValidator extends ConstraintValidator
{
    public function __construct(
        private CouponSentinel $couponSentinel
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->couponSentinel->isValidCoupon($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}