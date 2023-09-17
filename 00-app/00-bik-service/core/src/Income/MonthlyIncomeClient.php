<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Income;

use SmartTesting\Bik\Score\Cost\RestClient;
use SmartTesting\Bik\Score\Domain\Pesel;

class MonthlyIncomeClient
{
    private RestClient $restClient;
    private string $monthlyIncomeServiceUrl;

    public function __construct(RestClient $restClient, string $monthlyIncomeServiceUrl)
    {
        $this->restClient = $restClient;
        $this->monthlyIncomeServiceUrl = $monthlyIncomeServiceUrl;
    }

    public function getMonthlyIncome(Pesel $pesel): ?float
    {
        $monthlyIncome = $this->restClient->get($this->monthlyIncomeServiceUrl.'/'.$pesel->pesel());

        return is_numeric($monthlyIncome) ? (float) $monthlyIncome : null;
    }
}
