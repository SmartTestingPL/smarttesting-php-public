<?php

declare(strict_types=1);

namespace SmartTesting\Order;

/**
 * Reprezentuje promocję dla oferty pożyczek.
 */
class Promotion
{
    private string $name;
    private float $discount;

    public function __construct(string $name, float $discount)
    {
        $this->name = $name;
        $this->discount = $discount;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function discount(): float
    {
        return $this->discount;
    }
}
