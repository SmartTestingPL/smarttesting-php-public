<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Analysis;

use Prometheus\CollectorRegistry;
use Prometheus\Histogram;
use Psr\Log\LoggerInterface;
use SmartTesting\Bik\Score\Domain\Pesel;

final class ScoreAnalyzer
{
    private CompositeScoreEvaluation $compositeScoreEvaluation;

    private int $treshold;

    private Histogram $histogram;

    private LoggerInterface $logger;

    public function __construct(
        CompositeScoreEvaluation $compositeScoreEvaluation,
        int $treshold,
        CollectorRegistry $collectorRegistry,
        LoggerInterface $logger
    ) {
        $this->compositeScoreEvaluation = $compositeScoreEvaluation;
        $this->treshold = $treshold;
        $this->histogram = $collectorRegistry->getOrRegisterHistogram(
            'smart-testing',
            'score.aggregated',
            'Score points aggregated'
        );
    }

    public function shouldGrantLoan(Pesel $pesel): bool
    {
        $score = $this->compositeScoreEvaluation->aggregateAllScores($pesel);
        $this->histogram->observe($score->points());
        $aboveThreshold = $score->points() >= $this->treshold;
        $this->logger->info(sprintf(
            'For PESEL [%s] we got score [%s]. It\'s [%s] that it\'s above or equal to the threshold [%s]',
            $pesel->pesel(),
            $score->points(),
            $aboveThreshold ? 'true' : 'false',
            $this->treshold
        ));

        return $aboveThreshold;
    }
}
