<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Credit;

use SmartTesting\Bik\Score\Domain\Pesel;

interface CreditInfoRepository
{
    public function findCreditInfo(Pesel $pesel): ?CreditInfo;

    public function saveCreditInfo(Pesel $pesel, CreditInfo $creditInfo): CreditInfo;
}
