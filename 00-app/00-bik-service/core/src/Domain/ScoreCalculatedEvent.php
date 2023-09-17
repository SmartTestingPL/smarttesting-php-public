<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Domain;

final class ScoreCalculatedEvent
{
    public function __construct(
        private Pesel $pesel,
        private Score $score
    ) {
    }

    public function pesel(): Pesel
    {
        return $this->pesel;
    }

    public function score(): Score
    {
        return $this->score;
    }
}
