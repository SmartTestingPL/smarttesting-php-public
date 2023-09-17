<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Social;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class SocialStatusScoreEvaluation implements ScoreEvaluation
{
    private SocialStatusClient $client;

    public function __construct(SocialStatusClient $client)
    {
        $this->client = $client;
    }

    public function evaluate(Pesel $pesel): Score
    {
        $socialStatus = $this->client->getSocialStatus($pesel);

        return Score::zero()
            ->add($this->scoreForNoOfDependants($socialStatus))
            ->add($this->scoreForNoOfPeopleInTheHousehold($socialStatus))
            ->add($this->scoreForMaritalStatus($socialStatus))
            ->add($this->scoreForContractType($socialStatus))
        ;
    }

    private function scoreForNoOfDependants(SocialStatus $socialStatus): Score
    {
        return match ($socialStatus->noOfDependants()) {
            0 => new Score(50),
            1 => new Score(40),
            2 => new Score(30),
            3 => new Score(20),
            4 => new Score(10),
            default => Score::zero()
        };
    }

    private function scoreForNoOfPeopleInTheHousehold(SocialStatus $socialStatus): Score
    {
        $no = $socialStatus->noOfPeopleInTheHousehold();

        return match (true) {
            $no === 1 => new Score(50),
            $no > 1 && $no <= 2 => new Score(40),
            $no > 2 && $no < 3 => new Score(30),
            $no > 3 && $no <= 4 => new Score(20),
            $no > 4 && $no <= 5 => new Score(10),
            default => Score::zero()
        };
    }

    private function scoreForMaritalStatus(SocialStatus $socialStatus): Score
    {
        return match (true) {
            $socialStatus->maritalStatus()->equals(MaritalStatus::single()) => new Score(20),
            $socialStatus->maritalStatus()->equals(MaritalStatus::married()) => new Score(10)
        };
    }

    private function scoreForContractType(SocialStatus $socialStatus): Score
    {
        return match (true) {
            $socialStatus->contractType()->equals(ContractType::employmentContract()) => new Score(20),
            $socialStatus->contractType()->equals(ContractType::ownBusinessActivity()) => new Score(10),
            default => Score::zero()
        };
    }
}
