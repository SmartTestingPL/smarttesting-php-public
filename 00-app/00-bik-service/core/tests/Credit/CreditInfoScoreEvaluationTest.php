<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Credit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Bik\Score\Credit\CreditInfo;
use SmartTesting\Bik\Score\Credit\CreditInfoRepository;
use SmartTesting\Bik\Score\Credit\CreditInfoScoreEvaluation;
use SmartTesting\Bik\Score\Credit\DebtPaymentHistory;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Tests\CsvFileIterator;

final class CreditInfoScoreEvaluationTest extends TestCase
{
    private CreditInfoRepository|MockObject $repository;
    private CreditInfoScoreEvaluation $evaluation;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CreditInfoRepository::class);
        $this->evaluation = new CreditInfoScoreEvaluation($this->repository);
    }

    /**
     * @test
     */
    public function shouldReturnZeroForNullCreditInfo(): void
    {
        $this->repository->method('findCreditInfo')->willReturn(null);

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(0, $score->points());
    }

    /**
     * @test
     *
     * @dataProvider livingCostCsvProvider
     */
    public function shouldEvaluateScoreBasedOnCurrentLivingCost(string $livingCosts, string $points): void
    {
        $this->markTestSkipped();

        $this->repository->method('findCreditInfo')->willReturn(
            new CreditInfo(5501, (int) $livingCosts, DebtPaymentHistory::notASinglePaidInstallment())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame((int) $points, $score->points());
    }

    /**
     * @test
     *
     * @dataProvider currentDebtCsvProvider
     */
    public function shouldEvaluateScoreBasedOnCurrentDebt(string $currentDebt, string $points): void
    {
        $this->markTestSkipped();

        $this->repository->method('findCreditInfo')->willReturn(
            new CreditInfo((int) $currentDebt, 6501, DebtPaymentHistory::notASinglePaidInstallment())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame((int) $points, $score->points());
    }

    /**
     * @test
     */
    public function shouldEvaluateScoreForNotPayingCustomer(): void
    {
        $this->repository->method('findCreditInfo')->willReturn(
            new CreditInfo(5501, 6501, DebtPaymentHistory::notASinglePaidInstallment())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(0, $score->points());
    }

    /**
     * @test
     */
    public function shouldEvaluateScoreForAlwaysPayingCustomer(): void
    {
        $this->repository->method('findCreditInfo')->willReturn(
            new CreditInfo(5501, 6501, DebtPaymentHistory::notASingleUnpaidInstallment())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(50, $score->points());
    }

    /**
     * @test
     */
    public function shouldEvaluateScoreForOftenMissingPaymentCustomer(): void
    {
        $this->repository->method('findCreditInfo')->willReturn(
            new CreditInfo(5501, 6501, DebtPaymentHistory::multipleUnpaidInstallments())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(10, $score->points());
    }

    /**
     * @test
     */
    public function shouldEvaluateScoreForRarelyMissingPaymentCustomer(): void
    {
        $this->repository->method('findCreditInfo')->willReturn(
            new CreditInfo(5501, 6501, DebtPaymentHistory::individualUnpaidInstallments())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(30, $score->points());
    }

    public function livingCostCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../Resources/living-cost.csv', 1);
    }

    public function currentDebtCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../Resources/current-debt.csv', 1);
    }
}
