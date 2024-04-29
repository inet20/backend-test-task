<?php

namespace App\Service;

class TaxAccountant
{
    public function isValidTaxNumber(string $taxNumber): bool
    {
        return preg_match("/^DE\d{9}|IT\d{11}|GR\d{9}|FR[a-zA-Z]{2}\d{9}$/u", $taxNumber);
    }

    public function getTax(int $price, string $taxNumber): int
    {
        assert($this->isValidTaxNumber($taxNumber));

        return $price * $this->getTaxValue($taxNumber);
    }

    private function getTaxValue(string $taxNumber): float
    {
        switch (substr($taxNumber, 0, 2)) {
            case 'DE':
                return 0.19;
            case 'IT':
                return 0.22;
            case 'FR':
                return 0.2;
            case 'GR':
                return 0.24;
        }

        throw new \LogicException('Invalid tax number: ' . $taxNumber);
    }
}