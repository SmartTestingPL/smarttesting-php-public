<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Income;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class MonthlyIncomeScoreEvaluation implements ScoreEvaluation
{
    private MonthlyIncomeClient $client;

    public function __construct(MonthlyIncomeClient $client)
    {
        $this->client = $client;
    }

    public function evaluate(Pesel $pesel): Score
    {
        $monthlyIncome = $this->client->getMonthlyIncome($pesel);
        if ($this->between($monthlyIncome, 0, 500)) {
            return Score::zero();
        }
        if ($this->between($monthlyIncome, 501, 1500)) {
            return new Score(10);
        }
        if ($this->between($monthlyIncome, 1501, 3500)) {
            return new Score(20);
        }
        if ($this->between($monthlyIncome, 5501, 10000)) {
            return new Score(40);
        }

        return new Score(50);
    }

    private function between(float $income, float $min, float $max): bool
    {
        return $income >= $min && $income <= $max;
    }
}
