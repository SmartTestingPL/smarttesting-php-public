<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Personal;

use SmartTesting\Bik\Score\Domain\Score;

interface OccupationRepository
{
    /**
     * @return array<string, Score>
     */
    public function getOccupationScores(): array;
}
