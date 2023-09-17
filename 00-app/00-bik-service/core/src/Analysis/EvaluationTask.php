<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Analysis;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class EvaluationTask implements Task
{
    private ScoreEvaluation $scoreEvaluation;
    private Pesel $pesel;

    public function __construct(ScoreEvaluation $scoreEvaluation, Pesel $pesel)
    {
        $this->scoreEvaluation = $scoreEvaluation;
        $this->pesel = $pesel;
    }

    public function run(Environment $environment)
    {
        return $this->scoreEvaluation->evaluate($this->pesel);
    }
}
