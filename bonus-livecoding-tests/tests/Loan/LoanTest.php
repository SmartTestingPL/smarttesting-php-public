<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Loan;

use PHPUnit\Framework\TestCase;
use SmartTesting\Loan\Loan;
use SmartTesting\Order\Promotion;

final class LoanTest extends TestCase
{
    private const AMOUNT = 3000;
    private const INTEREST_RATE = 5;
    private const COMMISSION = 200;

    /**
     * @test
     */
    public function shouldCreateLoan(): void
    {
        $loanOrder = Fixtures::aLoanOrder(self::AMOUNT, self::INTEREST_RATE, self::COMMISSION);

        $loan = new Loan($date = new \DateTimeImmutable(), $loanOrder, 6);

        self::assertEquals($date, $loan->loanOpenedDate());
        self::assertEquals(6, $loan->numberOfInstallments());
        self::assertEquals(3350, $loan->amount());
    }

    /**
     * @test
     */
    public function shouldCalculateInstallmentAmount(): void
    {
        $loanOrder = Fixtures::aLoanOrder(self::AMOUNT, self::INTEREST_RATE, self::COMMISSION);

        $loanInstallment = (new Loan(new \DateTimeImmutable(), $loanOrder, 6))->installmentAmount();

        self::assertEquals(558.33, $loanInstallment);
    }

    /**
     * @test
     */
    public function shouldApplyPromotionDiscount(): void
    {
        $loanOrder = Fixtures::aLoanOrder(self::AMOUNT, self::INTEREST_RATE, self::COMMISSION,
            new Promotion('Test 10', 10), new Promotion('test 20', 20)
        );

        $loan = new Loan(new \DateTimeImmutable(), $loanOrder, 6);

        self::assertEquals(3320, $loan->amount());
        self::assertEquals(553.33, $loan->installmentAmount());
    }

    /**
     * @test
     */
    public function shouldApplyFixedDiscountIfPromotionDiscountSumHigherThanThreshold(): void
    {
        $loanOrder = Fixtures::aLoanOrder(2000, 5, 300,
            new Promotion('61', 61), new Promotion('300', 300)
        );

        // Base amount: 2400
        $loanAmount = (new Loan(new \DateTimeImmutable(), $loanOrder, 6))->amount();

        self::assertEquals(2040, $loanAmount);
    }
}
