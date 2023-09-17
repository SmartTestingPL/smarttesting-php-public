<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;

interface ScoreEvaluation
{
    public function evaluate(Pesel $pesel): Score;
}
