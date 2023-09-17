<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Personal;

use SmartTesting\Bik\Score\Cost\RestClient;
use SmartTesting\Bik\Score\Domain\Pesel;

class PersonalInformationClient
{
    private RestClient $restClient;
    private string $personalInformationServiceUrl;

    public function __construct(RestClient $restClient, string $personalInformationServiceUrl)
    {
        $this->restClient = $restClient;
        $this->personalInformationServiceUrl = $personalInformationServiceUrl;
    }

    public function getPersonalInformation(Pesel $pesel): ?PersonalInformation
    {
        $data = \json_decode($this->restClient->get($this->personalInformationServiceUrl.'/'.$pesel->pesel()), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return new PersonalInformation(
            Education::fromString($data['education']),
            $data['yearsOfWorkExperience'],
            Occupation::fromString($data['occupation'])
        );
    }
}
