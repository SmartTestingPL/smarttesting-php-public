<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Order;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

use SmartTesting\Order\LoanOrder;
use SmartTesting\Order\Promotion;

/**
 * Przykład zastosowania wzorca AssertObject.
 */
class LoanOrderAssert
{
    private LoanOrder $loanOrder;

    public function __construct(LoanOrder $loanOrder)
    {
        $this->loanOrder = $loanOrder;
    }

    public static function then(LoanOrder $loadOrder): self
    {
        return new self($loadOrder);
    }

    public function registeredToday(): self
    {
        assertEquals((new \DateTimeImmutable())->setTime(0, 0, 0, 0), $this->loanOrder->orderDate());

        return $this;
    }

    public function hasPromotion(string $promotionName): self
    {
        assertCount(1, array_filter($this->loanOrder->promotions(), fn (Promotion $promotion) => $promotion->name() === $promotionName));

        return $this;
    }

    public function hasOnlyOnePromotion(): self
    {
        assertCount(1, $this->loanOrder->promotions());

        return $this;
    }

    public function hasPromotionNumber(int $number): self
    {
        assertCount($number, $this->loanOrder->promotions());

        return $this;
    }

    public function firstPromotionHasDiscountValue(float $discount): self
    {
        assertEquals($discount, $this->loanOrder->promotions()[0]->discount());

        return $this;
    }

    public function correctStudentLoadOrder(): self
    {
        return $this->registeredToday()
            ->hasPromotion('Student Promo')
            ->hasOnlyOnePromotion()
            ->firstPromotionHasDiscountValue(10.0);
    }
}
