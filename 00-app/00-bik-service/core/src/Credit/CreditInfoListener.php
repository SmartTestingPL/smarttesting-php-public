<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Credit;

use SmartTesting\Bik\Score\Domain\Pesel;

interface CreditInfoListener
{
    public function storeCreditInfo(Pesel $pesel, CreditInfo $creditInfo);
}
