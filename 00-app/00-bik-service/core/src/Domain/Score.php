<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Domain;

final class Score
{
    private int $points;

    public function __construct(int $points)
    {
        $this->points = $points;
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function points(): int
    {
        return $this->points;
    }

    public function setPoints(int $points): void
    {
        $this->points = $points;
    }

    public function add(self $score): self
    {
        return new self($this->points + $score->points);
    }
}
