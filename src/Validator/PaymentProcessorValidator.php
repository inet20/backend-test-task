<?php

namespace App\Validator;

use App\Service\Merchant;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PaymentProcessorValidator extends ConstraintValidator
{
    public function __construct(private readonly Merchant $merchant)
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

        if (!$this->merchant->isValidPaymentProcessor($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}