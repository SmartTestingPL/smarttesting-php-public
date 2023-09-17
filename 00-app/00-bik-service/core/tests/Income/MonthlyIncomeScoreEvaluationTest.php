<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Income;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Income\MonthlyIncomeClient;
use SmartTesting\Bik\Score\Income\MonthlyIncomeScoreEvaluation;
use SmartTesting\Bik\Score\Tests\CsvFileIterator;

final class MonthlyIncomeScoreEvaluationTest extends TestCase
{
    private MonthlyIncomeClient|MockObject $client;
    private MonthlyIncomeScoreEvaluation $evaluation;

    protected function setUp(): void
    {
        $this->client = $this->createMock(MonthlyIncomeClient::class);
        $this->evaluation = new MonthlyIncomeScoreEvaluation($this->client);
    }

    /**
     * @test
     *
     * @dataProvider monthlyIncomeCsvProvider
     */
    public function shouldCalculateScoreBasedOnMonthlyIncome(string $monthlyIncome, string $points): void
    {
        $this->markTestSkipped();

        $this->client->method('getMonthlyIncome')->willReturn((float) $monthlyIncome);

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame((int) $points, $score->points());
    }

    /**
     * @test
     */
    public function shouldReturnZeroWhenMonthlyIncomeNull(): void
    {
        $this->markTestSkipped();

        $this->client->method('getMonthlyIncome')->willReturn(null);

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(0, $score->points());
    }

    public function monthlyIncomeCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../Resources/monthly-income.csv', 1);
    }
}
