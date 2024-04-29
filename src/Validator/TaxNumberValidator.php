<?php

namespace App\Validator;

use App\Service\TaxAccountant;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TaxNumberValidator extends ConstraintValidator
{
    public function __construct(
        private TaxAccountant $taxAccountant
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

        if (!$this->taxAccountant->isValidTaxNumber($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}