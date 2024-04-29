<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

class PayloadException extends \Exception
{
    public function __construct(
        string $message,
        public readonly ?ConstraintViolationList $violations = null,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}