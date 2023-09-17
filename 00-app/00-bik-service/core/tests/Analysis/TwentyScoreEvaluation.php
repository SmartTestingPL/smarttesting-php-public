<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Analysis;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class TwentyScoreEvaluation implements ScoreEvaluation
{
    public function evaluate(Pesel $pesel): Score
    {
        return new Score(20);
    }
}
