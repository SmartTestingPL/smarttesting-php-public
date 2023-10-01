<?php

declare(strict_types=1);

namespace SmartTesting\Order;

use SmartTesting\Customer\Customer;
use SmartTesting\Loan\LoanType;
use Symfony\Component\Uid\Uuid;

/**
 * Reprezentuje wniosek o udzielenie pożyczki.
 */
class LoanOrder
{
    private \DateTimeImmutable $orderDate;
    private Customer $customer;
    private LoanType $type;
    private float $amount;
    private float $interestRate;
    private float $commission;

    /**
     * @var Promotion[]
     */
    private array $promotions = [];

    public function __construct(\DateTimeImmutable $orderDate, Customer $customer, float $amount, float $interestRate, float $commission)
    {
        $this->orderDate = $orderDate;
        $this->customer = $customer;
        $this->amount = $amount;
        $this->interestRate = $interestRate;
        $this->commission = $commission;
    }

    public function addManagerDiscount(Uuid $managerId): void
    {
        $this->addPromotion(new Promotion('Manager Promo: '.(string) $managerId, 50.0));
    }

    public function orderDate(): \DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function customer(): Customer
    {
        return $this->customer;
    }

    public function type(): LoanType
    {
        return $this->type;
    }

    public function setType(LoanType $type): void
    {
        $this->type = $type;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function interestRate(): float
    {
        return $this->interestRate;
    }

    public function commission(): float
    {
        return $this->commission;
    }

    public function promotions(): array
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): void
    {
        $this->promotions[] = $promotion;
    }

    /**
     * @param Promotion[] $promotions
     */
    public function setPromotions(array $promotions): void
    {
        $this->promotions = $promotions;
    }
}
