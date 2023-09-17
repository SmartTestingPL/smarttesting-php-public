<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Personal;

final class PersonalInformation
{
    private Education $education;
    private int $yearsOfWorkExperience;
    private Occupation $occupation;

    public function __construct(Education $education, int $yearsOfWorkExperience, Occupation $occupation)
    {
        $this->education = $education;
        $this->yearsOfWorkExperience = $yearsOfWorkExperience;
        $this->occupation = $occupation;
    }

    public function education(): Education
    {
        return $this->education;
    }

    public function yearsOfWorkExperience(): int
    {
        return $this->yearsOfWorkExperience;
    }

    public function occupation(): Occupation
    {
        return $this->occupation;
    }
}
