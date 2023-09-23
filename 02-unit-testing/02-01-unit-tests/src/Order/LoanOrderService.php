<?php

declare(strict_types=1);

namespace SmartTesting\Order;

use SmartTesting\Customer\Customer;
use SmartTesting\Loan\LoanType;

/**
 * Serwis procesujący przyznawanie pożyczek w zależności od typu pożyczki i obowiązujących promocji.
 */
class LoanOrderService
{
    private PostgresAccessor $postgresAccessor;
    private MongoDbAccessor $mongoDbAccessor;

    public function __construct(PostgresAccessor $postgresAccessor, MongoDbAccessor $mongoDbAccessor)
    {
        $this->postgresAccessor = $postgresAccessor;
        $this->mongoDbAccessor = $mongoDbAccessor;
    }

    public function studentLoanOrder(Customer $customer): LoanOrder
    {
        if (!$customer->isStudent()) {
            throw new \InvalidArgumentException('Cannot order student loan if Customer is not a student');
        }
        $loadOrder = new LoanOrder((new \DateTimeImmutable())->setTime(0, 0, 0, 0), $customer);
        $loadOrder->setType(LoanType::STUDENT());

        $discount = $this->mongoDbAccessor->getPromotionDiscount('Student Promo');
        $loadOrder->addPromotion(new Promotion('Student Promo', $discount));
        $loadOrder->setCommission(200.0);

        $this->postgresAccessor->updatePromotionStatistics('Student Promo');

        return $loadOrder;
    }
}
