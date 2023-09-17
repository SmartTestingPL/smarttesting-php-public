<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Credit;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class CreditInfoScoreEvaluation implements ScoreEvaluation
{
    private CreditInfoRepository $creditInfoRepository;

    public function __construct(CreditInfoRepository $creditInfoRepository)
    {
        $this->creditInfoRepository = $creditInfoRepository;
    }

    public function evaluate(Pesel $pesel): Score
    {
        $creditInfo = $this->creditInfoRepository->findCreditInfo($pesel);
        if ($creditInfo === null) {
            return Score::zero();
        }

        return Score::zero()
            ->add($this->scoreForCurrentDebt($creditInfo->currentDebt()))
            ->add($this->scoreForCurrentLivingCosts($creditInfo->currentLivingCosts()))
            ->add($this->scoreForDebtPaymentHistory($creditInfo->debtPaymentHistory()));
    }

    private function scoreForCurrentDebt(float $currentDebt): Score
    {
        if ($this->between($currentDebt, 5501, 10000)) {
            return Score::zero();
        }
        if ($this->between($currentDebt, 3501, 5500)) {
            return new Score(10);
        }
        if ($this->between($currentDebt, 1501, 3500)) {
            return new Score(20);
        }
        if ($this->between($currentDebt, 500, 1500)) {
            return new Score(40);
        }

        return new Score(50);
    }

    private function scoreForCurrentLivingCosts(float $currentDebt): Score
    {
        if ($this->between($currentDebt, 6501, 10000)) {
            return Score::zero();
        }
        if ($this->between($currentDebt, 4501, 6500)) {
            return new Score(10);
        }
        if ($this->between($currentDebt, 2501, 4500)) {
            return new Score(20);
        }
        if ($this->between($currentDebt, 1000, 2500)) {
            return new Score(40);
        }

        return new Score(50);
    }

    private function scoreForDebtPaymentHistory(DebtPaymentHistory $debtPaymentHistory): Score
    {
        return match (true) {
            $debtPaymentHistory->equals(DebtPaymentHistory::multipleUnpaidInstallments()) => new Score(10),
            $debtPaymentHistory->equals(DebtPaymentHistory::notASingleUnpaidInstallment()) => new Score(50),
            $debtPaymentHistory->equals(DebtPaymentHistory::individualUnpaidInstallments()) => new Score(30),
            default => Score::zero()
        };
    }

    private function between(float $currentDebt, float $min, float $max): bool
    {
        return $currentDebt >= $min && $currentDebt <= $max;
    }
}
