<?php

declare(strict_types=1);

namespace SmartTesting\Order;

use SmartTesting\Customer\Customer;
use SmartTesting\Loan\LoanType;
use Symfony\Component\Uid\Uuid;

/**
 * Reprezentuje wniosek o udzielenie pożyczki.
 */
class LoanOrder implements \JsonSerializable
{
    private Uuid $id;
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

    private string $status;

    public function __construct(Uuid $id, \DateTimeImmutable $orderDate, Customer $customer)
    {
        $this->id = $id;
        $this->orderDate = $orderDate;
        $this->customer = $customer;
        $this->status = 'new';
    }

    public function id(): Uuid
    {
        return $this->id;
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

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
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

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function interestRate(): float
    {
        return $this->interestRate;
    }

    public function setInterestRate(float $interestRate): void
    {
        $this->interestRate = $interestRate;
    }

    public function commission(): float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): void
    {
        $this->commission = $commission;
    }

    public function promotions(): array
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): void
    {
        $this->promotions[] = $promotion;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer,
            'orderDate' => $this->orderDate->format('Y-m-d'),
            'status' => $this->status,
        ];
    }
}
