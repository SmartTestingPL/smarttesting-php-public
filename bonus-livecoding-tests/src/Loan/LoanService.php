<?php

declare(strict_types=1);

namespace SmartTesting\Loan;

use SmartTesting\Db\MongoDbAccessor;
use SmartTesting\Db\PostgresAccessor;
use SmartTesting\Event\EventEmitter;
use SmartTesting\Loan\Validation\CommissionValidationException;
use SmartTesting\Loan\Validation\NumberOfInstallmentsValidationException;
use SmartTesting\Order\LoanOrder;
use SmartTesting\Order\Promotion;

final class LoanService
{
    public function __construct(
        private EventEmitter $eventEmitter,
        private MongoDbAccessor $mongoDbAccessor,
        private PostgresAccessor $postgresAccessor
    ) {
    }

    public function createLoan(LoanOrder $loanOrder, int $numberOfInstallments): Loan
    {
        // Forget to pass argument (validate field instead)
        $this->validateNumberOfInstallments($numberOfInstallments);
        // Forget to add this method add first
        $this->validateCommission($loanOrder->commission());
        $this->updatePromotions($loanOrder);
        $loan = new Loan(new \DateTimeImmutable(), $loanOrder, $numberOfInstallments);
        $this->eventEmitter->emit(new LoanCreatedEvent($loan->uuid()));

        return $loan;
    }

    private function validateCommission(float $commission): void
    {
        if ($commission < $this->mongoDbAccessor->getMinCommission()) {
            throw new CommissionValidationException();
        }
    }

    private function validateNumberOfInstallments(int $numberOfInstallments): void
    {
        if ($numberOfInstallments <= 0) {
            throw new NumberOfInstallmentsValidationException();
        }
    }

    // Visible for tests
    // Potencjalny kandydat na osobną klasę
    // TODO: private
    public function updatePromotions(LoanOrder $loanOrder): void
    {
        $updatedPromotions = array_filter(
            $loanOrder->promotions(),
            fn (Promotion $promotion) => in_array($promotion, $this->postgresAccessor->getValidPromotionsForDate(new \DateTimeImmutable()), true)
        );
        $loanOrder->setPromotions($updatedPromotions);
    }
}
