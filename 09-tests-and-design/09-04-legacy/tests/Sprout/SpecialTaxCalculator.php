<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Sprout;

class SpecialTaxCalculator
{
    private const AMOUNT_THRESHOLD = 10;
    private const TAX_MULTIPLIER = 20;
    private int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function calculate(): int
    {
        if ($this->amount <= self::AMOUNT_THRESHOLD) {
            return $this->amount;
        }

        return $this->amount * self::TAX_MULTIPLIER;
    }
}
