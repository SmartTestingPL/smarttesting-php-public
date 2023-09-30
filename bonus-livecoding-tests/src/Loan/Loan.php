<?php

declare(strict_types=1);

namespace SmartTesting\Loan;

use SmartTesting\Loan\Validation\LoanValidationException;
use SmartTesting\Order\LoanOrder;
use SmartTesting\Order\Promotion;
use Symfony\Component\Uid\Uuid;

final class Loan
{
    private \DateTimeImmutable $loanOpenedDate;
    private float $amount;
    private int $numberOfInstallments;
    private float $installmentAmount;
    private Uuid $uuid;

    public function __construct(\DateTimeImmutable $loanOpenedDate, LoanOrder $loanOrder, int $numberOfInstallments)
    {
        $this->loanOpenedDate = $loanOpenedDate;
        $this->amount = $this->calculateLoanAmount($loanOrder);
        $this->numberOfInstallments = $numberOfInstallments;
        $this->installmentAmount = round($this->amount / $numberOfInstallments, 2, PHP_ROUND_HALF_EVEN);
        $this->uuid = Uuid::v4();
    }

    public function loanOpenedDate(): \DateTimeImmutable
    {
        return $this->loanOpenedDate;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function numberOfInstallments(): int
    {
        return $this->numberOfInstallments;
    }

    public function installmentAmount(): float
    {
        return $this->installmentAmount;
    }

    public function uuid()
    {
        return $this->uuid;
    }

    private function calculateLoanAmount(LoanOrder $loanOrder): float
    {
        $this->validateElement($loanOrder->amount());
        $this->validateElement($loanOrder->interestRate());
        $this->validateElement($loanOrder->commission());
        $interestFactor = 1 + (round($loanOrder->interestRate() / 100, 2, PHP_ROUND_HALF_UP));
        $baseAmount = ($loanOrder->amount() * $interestFactor) + $loanOrder->commission();

        return $this->applyPromotionDiscounts($loanOrder, $baseAmount);
    }

    private function applyPromotionDiscounts(LoanOrder $loanOrder, float $baseAmount): float
    {
        $discountSum = array_sum(array_map(fn (Promotion $p) => $p->discount(), $loanOrder->promotions()));
        $fifteenPercentOfBaseSum = round(($baseAmount * 15) / 100, 2, PHP_ROUND_HALF_EVEN);
        if ($fifteenPercentOfBaseSum <= $discountSum) {
            return $baseAmount - $fifteenPercentOfBaseSum;
        }

        return $baseAmount - $discountSum;
    }

    private function validateElement(float $elementAmount): void
    {
        if ($elementAmount < 1) {
            throw new LoanValidationException();
        }
    }
}
