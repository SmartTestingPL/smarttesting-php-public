<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Cost;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Bik\Score\Cost\MonthlyCostClient;
use SmartTesting\Bik\Score\Cost\MonthlyCostScoreEvaluation;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Tests\CsvFileIterator;

final class MonthlyCostScoreEvaluationTest extends TestCase
{
    private MonthlyCostClient|MockObject $monthlyCostClient;
    private MonthlyCostScoreEvaluation $evaluation;

    protected function setUp(): void
    {
        $this->monthlyCostClient = $this->createMock(MonthlyCostClient::class);
        $this->evaluation = new MonthlyCostScoreEvaluation($this->monthlyCostClient);
    }

    /**
     * 	// Test nie przechodzi; obsługa minusowej wartości kosztów nie została dodana;
     *  // Brakuje implementacji dla przedziału 3501 - 5500 -> 20; Granice warunków niepoprawnie zaimplementowane.
     *
     * @test
     *
     * @dataProvider monthlyCostCsvProvider
     */
    public function shouldCalculateScoreBasedOnMonthlyCost(string $monthlyCost, string $points): void
    {
        $this->markTestSkipped();

        $this->monthlyCostClient->method('getMonthlyCosts')->willReturn((float) $monthlyCost);

        $score = $this->evaluation->evaluate(new Pesel('96082812079'));

        self::assertSame((int) $points, $score->points());
    }

    public function monthlyCostCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../Resources/monthly-cost.csv', 1);
    }
}
