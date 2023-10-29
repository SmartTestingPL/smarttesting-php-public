<?php

declare(strict_types=1);

namespace SmartTesting\Order;

use SmartTesting\Fraud\FraudWebClient;
use SmartTesting\Repository\LoanOrderRepository;
use Symfony\Component\Uid\Uuid;

class LoanOrderService
{
    private LoanOrderRepository $repository;
    private FraudWebClient $fraudClient;

    public function __construct(LoanOrderRepository $repository, FraudWebClient $fraudClient)
    {
        $this->repository = $repository;
        $this->fraudClient = $fraudClient;
    }

    public function verifyLoanOrder(LoanOrder $loanOrder): string
    {
        $result = $this->fraudClient->verifyCustomer($loanOrder->customer());
        $loanOrder->setStatus($result->isPassed() ? 'verified' : 'rejected');
        $this->repository->save($loanOrder);

        return (string) $loanOrder->id();
    }

    public function findOrder(string $orderId): ?LoanOrder
    {
        return $this->repository->findById(Uuid::fromString($orderId));
    }
}
