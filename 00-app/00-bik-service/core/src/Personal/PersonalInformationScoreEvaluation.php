<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Personal;

use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\ScoreEvaluation;

final class PersonalInformationScoreEvaluation implements ScoreEvaluation
{
    private PersonalInformationClient $client;
    private OccupationRepository $occupationRepository;

    public function __construct(PersonalInformationClient $client, OccupationRepository $occupationRepository)
    {
        $this->client = $client;
        $this->occupationRepository = $occupationRepository;
    }

    public function evaluate(Pesel $pesel): Score
    {
        $personalInformation = $this->client->getPersonalInformation($pesel);

        return Score::zero()
            ->add($this->scoreForOccupation($personalInformation->occupation()))
            ->add($this->scoreForEducation($personalInformation->education()))
            ->add($this->scoreForYearsOfWorkExperience($personalInformation->yearsOfWorkExperience()))
        ;
    }

    private function scoreForOccupation(Occupation $occupation): Score
    {
        $occupationToScore = $this->occupationRepository->getOccupationScores();

        return $occupationToScore[$occupation->toString()] ?? Score::zero();
    }

    private function scoreForEducation(Education $education): Score
    {
        return match (true) {
            $education->equals(Education::basic()) => new Score(10),
            $education->equals(Education::high()) => new Score(50),
            $education->equals(Education::medium()) => new Score(30),
            default => Score::zero()
        };
    }

    private function scoreForYearsOfWorkExperience(int $yearsOfWorkExperience): Score
    {
        if ($yearsOfWorkExperience === 1) {
            return new Score(5);
        }
        if ($yearsOfWorkExperience >= 2 && $yearsOfWorkExperience < 5) {
            return new Score(10);
        }
        if ($yearsOfWorkExperience >= 5 && $yearsOfWorkExperience < 10) {
            return new Score(20);
        }
        if ($yearsOfWorkExperience >= 10 && $yearsOfWorkExperience < 15) {
            return new Score(30);
        }
        if ($yearsOfWorkExperience >= 15 && $yearsOfWorkExperience < 20) {
            return new Score(40);
        }
        if ($yearsOfWorkExperience >= 20 && $yearsOfWorkExperience < 30) {
            return new Score(50);
        }

        return Score::zero();
    }
}
