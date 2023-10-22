<?php

declare(strict_types=1);

namespace SmartTesting\Loan;

/**
 * Typ pożyczki: studencka bądź zwykła.
 */
final class LoanType
{
    private const REGULAR = 'regular';
    private const STUDENT = 'student';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function REGULAR(): self
    {
        return new self(self::REGULAR);
    }

    public static function STUDENT(): self
    {
        return new self(self::STUDENT);
    }
}
