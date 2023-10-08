<?php

declare(strict_types=1);

namespace SmartTesting\Repository;

use SmartTesting\Order\LoanOrder;
use Symfony\Component\Uid\Uuid;

class TempFileLoanOrderRepository implements LoanOrderRepository
{
    private string $dir;

    public function __construct()
    {
        $this->dir = sys_get_temp_dir().'/php-loan-orders';
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }

    public function findById(Uuid $orderId): ?LoanOrder
    {
        if (!file_exists($this->dir.'/'.(string) $orderId)) {
            return null;
        }

        return unserialize(file_get_contents($this->dir.'/'.(string) $orderId));
    }

    public function save(LoanOrder $loanOrder): void
    {
        file_put_contents($this->dir.'/'.(string) $loanOrder->id(), serialize($loanOrder));
    }
}
