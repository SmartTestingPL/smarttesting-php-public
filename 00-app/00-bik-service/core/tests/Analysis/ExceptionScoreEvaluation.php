<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Analysis;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class ExceptionScoreEvaluation implements ScoreEvaluation
{
    public function evaluate(Pesel $pesel): Score
    {
        throw new \RuntimeException('Boom!');
    }
}
