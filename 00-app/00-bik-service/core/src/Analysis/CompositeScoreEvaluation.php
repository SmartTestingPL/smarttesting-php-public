<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Analysis;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;

interface CompositeScoreEvaluation
{
    public function aggregateAllScores(Pesel $pesel): Score;
}
