<?php

declare(strict_types=1);

namespace SmartTesting\Repository;

use SmartTesting\Order\LoanOrder;
use Symfony\Component\Uid\Uuid;

interface LoanOrderRepository
{
    public function findById(Uuid $orderId): ?LoanOrder;

    public function save(LoanOrder $loanOrder): void;
}
