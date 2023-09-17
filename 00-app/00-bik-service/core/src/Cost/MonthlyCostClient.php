<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Cost;

use SmartTesting\Bik\Score\Domain\Pesel;

class MonthlyCostClient
{
    private RestClient $restClient;
    private string $monthlyCostServiceUrl;

    public function __construct(RestClient $restClient, string $monthlyCostServiceUrl)
    {
        $this->restClient = $restClient;
        $this->monthlyCostServiceUrl = $monthlyCostServiceUrl;
    }

    public function getMonthlyCosts(Pesel $pesel): float
    {
        $monthlyCostString = $this->restClient->get($this->monthlyCostServiceUrl.'/'.$pesel->pesel());

        return (float) $monthlyCostString;
    }
}
