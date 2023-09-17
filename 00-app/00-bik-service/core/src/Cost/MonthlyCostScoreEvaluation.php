<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Cost;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class MonthlyCostScoreEvaluation implements ScoreEvaluation
{
    private MonthlyCostClient $monthlyCostClient;

    public function __construct(MonthlyCostClient $monthlyCostClient)
    {
        $this->monthlyCostClient = $monthlyCostClient;
    }

    // TODO: Brakuje obslugi bledow (co jesli wywali blad - jak traktujemy usera)
    // TODO: Konieczne testy do edge casow
    public function evaluate(Pesel $pesel): Score
    {
        $monthlyCosts = $this->monthlyCostClient->getMonthlyCosts($pesel);
        // 0 - 500 - 50
        // 501 - 1500 - 40
        // 1501 - 3500 - 30
        // 3501 - 5500 - 20 [BRAKUJE]
        // 5501 - 10000 - 10
        // 10000 > 0
        // TODO: Brakuje jednego przypadku
        if ($this->between($monthlyCosts, 0, 500)) {
            return new Score(50);
        }
        if ($this->between($monthlyCosts, 501, 1500)) {
            return new Score(40);
        }
        if ($this->between($monthlyCosts, 1501, 3500)) {
            return new Score(30);
        }
        if ($this->between($monthlyCosts, 5501, 10000)) {
            return new Score(10);
        }

        return Score::zero();
    }

    private function between(float $income, float $min, float $max): bool
    {
        // TODO: [Błąd] Biznesowo powinno byc mniejsze rowne
        return $income >= $min && $income < $max;
    }
}
