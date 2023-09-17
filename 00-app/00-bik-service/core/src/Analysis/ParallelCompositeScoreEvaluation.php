<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Analysis;

use Amp\Parallel\Worker;
use Amp\Promise;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\Domain\ScoreCalculatedEvent;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class ParallelCompositeScoreEvaluation implements CompositeScoreEvaluation
{
    /**
     * @var ScoreEvaluation[]
     */
    private array $scoreEvaluations;

    private ScoreUpdater $scoreUpdater;

    public function __construct(array $scoreEvaluations, ScoreUpdater $scoreUpdater)
    {
        $this->scoreEvaluations = $scoreEvaluations;
        $this->scoreUpdater = $scoreUpdater;
    }

    // TODO: Brak obslugi bledow
    // TODO: Biznesowo jak blad - to Score.ZERO
    public function aggregateAllScores(Pesel $pesel): Score
    {
        $promises = [];
        foreach ($this->scoreEvaluations as $evaluation) {
            $promises[] = Worker\enqueue(new EvaluationTask($evaluation, $pesel));
        }
        $score = new Score(array_sum(array_map(fn (Score $score) => $score->points(), Promise\wait(Promise\all($promises)))));
        $this->scoreUpdater->scoreCalculated(new ScoreCalculatedEvent($pesel, $score));

        return $score;
    }
}
