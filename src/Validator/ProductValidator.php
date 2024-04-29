<?php

namespace App\Validator;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ProductValidator extends ConstraintValidator
{
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->productRepository = $entityManager->getRepository(Product::class);
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        $product = $this->productRepository->find($value);

        if ($product == null) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}