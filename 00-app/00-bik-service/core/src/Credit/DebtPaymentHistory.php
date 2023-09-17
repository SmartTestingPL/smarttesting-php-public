<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Credit;

final class DebtPaymentHistory
{
    private const NOT_A_SINGLE_PAID_INSTALLMENT = 'not-a-single-paid-installment';
    private const MULTIPLE_UNPAID_INSTALLMENTS = 'multiple-unpaid-installments';
    private const INDIVIDUAL_UNPAID_INSTALLMENTS = 'individual-unpaid-installments';
    private const NOT_A_SINGLE_UNPAID_INSTALLMENT = 'not-a-single-unpaid-installment';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function notASinglePaidInstallment(): self
    {
        return new self(self::NOT_A_SINGLE_PAID_INSTALLMENT);
    }

    public static function multipleUnpaidInstallments(): self
    {
        return new self(self::MULTIPLE_UNPAID_INSTALLMENTS);
    }

    public static function individualUnpaidInstallments(): self
    {
        return new self(self::INDIVIDUAL_UNPAID_INSTALLMENTS);
    }

    public static function notASingleUnpaidInstallment(): self
    {
        return new self(self::NOT_A_SINGLE_UNPAID_INSTALLMENT);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
