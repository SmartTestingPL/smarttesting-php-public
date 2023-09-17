<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Social;

final class SocialStatus
{
    private int $noOfDependants;
    private int $noOfPeopleInTheHousehold;
    private MaritalStatus $maritalStatus;
    private ContractType $contractType;

    public function __construct(int $noOfDependants, int $noOfPeopleInTheHousehold, MaritalStatus $maritalStatus, ContractType $contractType)
    {
        $this->noOfDependants = $noOfDependants;
        $this->noOfPeopleInTheHousehold = $noOfPeopleInTheHousehold;
        $this->maritalStatus = $maritalStatus;
        $this->contractType = $contractType;
    }

    public function noOfDependants(): int
    {
        return $this->noOfDependants;
    }

    public function noOfPeopleInTheHousehold(): int
    {
        return $this->noOfPeopleInTheHousehold;
    }

    public function maritalStatus(): MaritalStatus
    {
        return $this->maritalStatus;
    }

    public function contractType(): ContractType
    {
        return $this->contractType;
    }
}
