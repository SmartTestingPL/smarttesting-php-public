<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Order;

use SmartTesting\Order\LoanOrder;
use Symfony\Component\Uid\Uuid;

/**
 * Przykład zastosowania klasy bazowej w celu zwiększenia czytelności i umożliwienia reużycia kodu.
 * Przykład testowania stanu.
 */
class LoanOrderTest extends LoanOrderTestBase
{
    public function testShouldAddManagerPromo(): void
    {
        $loanOrder = new LoanOrder(new \DateTimeImmutable(), $this->aCustomer());
        $managerId = Uuid::v4();

        $loanOrder->addManagerDiscount($managerId);

        self::assertCount(1, $loanOrder->promotions());
        self::assertCount(1, array_filter(
            $loanOrder->promotions(),
            fn ($promotion) => strstr($promotion->name(), (string) $managerId) !== false
        ));
        self::assertEquals(50.0, $loanOrder->promotions()[0]->discount());
    }
}
