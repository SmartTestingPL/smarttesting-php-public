<?php

declare(strict_types=1);

namespace SmartTesting\Order;

use SmartTesting\Customer\Customer;
use SmartTesting\Loan\LoanType;

/**
 * Serwis procesujący przynawanie pożyczek w zależności od typu pożyczki i obowiązujących promocji.
 */
class LoanOrderService
{
    public function studentLoanOrder(Customer $customer): LoanOrder
    {
        if (!$customer->isStudent()) {
            throw new \InvalidArgumentException('Cannot order student loan if Customer is not a student');
        }
        $loadOrder = new LoanOrder((new \DateTimeImmutable())->setTime(0, 0, 0, 0), $customer);
        $loadOrder->setType(LoanType::STUDENT());
        $loadOrder->addPromotion(new Promotion('Student Promo', 10.0));
        $loadOrder->setCommission(200.0);

        return $loadOrder;
    }
}
