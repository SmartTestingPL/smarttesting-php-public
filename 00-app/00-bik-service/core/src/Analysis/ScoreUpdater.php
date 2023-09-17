<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Analysis;

use SmartTesting\Bik\Score\Domain\ScoreCalculatedEvent;

interface ScoreUpdater
{
    public function scoreCalculated(ScoreCalculatedEvent $scoreCalculatedEvent);
}
