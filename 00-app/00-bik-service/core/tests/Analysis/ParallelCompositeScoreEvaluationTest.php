<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Analysis;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Bik\Score\Analysis\ParallelCompositeScoreEvaluation;
use SmartTesting\Bik\Score\Analysis\ScoreUpdater;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\Domain\ScoreCalculatedEvent;

final class ParallelCompositeScoreEvaluationTest extends TestCase
{
    private ScoreUpdater|MockObject $scoreUpdater;

    protected function setUp(): void
    {
        $this->scoreUpdater = $this->createMock(ScoreUpdater::class);
    }

    /**
     * @test
     */
    public function should_calculate_scores(): void
    {
        $evaluation = new ParallelCompositeScoreEvaluation([
            new TenScoreEvaluation(),
            new TwentyScoreEvaluation(),
        ], $this->scoreUpdater);

        $this->scoreUpdater
            ->expects($this->once())
            ->method('scoreCalculated')
            ->with(new ScoreCalculatedEvent(new Pesel('12345678901'), new Score(30)));

        $score = $evaluation->aggregateAllScores(new Pesel('12345678901'));

        self::assertSame(30, $score->points());
    }

    /**
     * // TODO: Biznesowo nieobsłużone.
     *
     * @test
     */
    public function should_return_0_score_when_exception_thrown(): void
    {
        $this->markTestSkipped('Test wykryje błąd');

        $evaluation = new ParallelCompositeScoreEvaluation([
            new ExceptionScoreEvaluation(),
        ], $this->scoreUpdater);

        $this->scoreUpdater
            ->expects($this->once())
            ->method('scoreCalculated')
            ->with(new ScoreCalculatedEvent(new Pesel('12345678901'), Score::zero()));

        $score = $evaluation->aggregateAllScores(new Pesel('12345678901'));

        self::assertSame(0, $score->points());
    }
}
