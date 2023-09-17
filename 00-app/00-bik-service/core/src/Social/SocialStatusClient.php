<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Social;

use SmartTesting\Bik\Score\Cost\RestClient;
use SmartTesting\Bik\Score\Domain\Pesel;

class SocialStatusClient
{
    private RestClient $restClient;
    private string $socialStatusServiceUrl;

    public function __construct(RestClient $restClient, string $socialStatusServiceUrl)
    {
        $this->restClient = $restClient;
        $this->socialStatusServiceUrl = $socialStatusServiceUrl;
    }

    public function getSocialStatus(Pesel $pesel): ?SocialStatus
    {
        $data = \json_decode($this->restClient->get($this->socialStatusServiceUrl.'/'.$pesel->pesel()), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return new SocialStatus(
            $data['noOfDependants'],
            $data['noOfPeopleInTheHousehold'],
            MaritalStatus::fromString($data['maritalStatus']),
            ContractType::fromString($data['contractType'])
        );
    }
}
