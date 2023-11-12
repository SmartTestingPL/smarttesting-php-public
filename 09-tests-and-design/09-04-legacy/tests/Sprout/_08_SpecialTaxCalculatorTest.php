<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Sprout;

use PHPUnit\Framework\TestCase;

class _08_SpecialTaxCalculatorTest extends TestCase
{
    public function test_should_not_apply_special_tax_when_amount_not_reaching_threshold(): void
    {
        $initialAmount = 8;
        $calculator = new SpecialTaxCalculator($initialAmount);

        self::assertEquals($initialAmount, $calculator->calculate());
    }

    public function test_should_apply_special_tax_when_amount_reaches_threshold(): void
    {
        $initialAmount = 25;
        $calculator = new SpecialTaxCalculator($initialAmount);

        self::assertEquals(500, $calculator->calculate());
    }
}
