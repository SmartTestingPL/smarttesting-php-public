<?php

declare(strict_types=1);

namespace SmartTesting\Order;

/**
 * Interfejs służący do komunikacji z relacyjną bazą danych.
 * Posłuży nam do przykładów zastosowania mocków i weryfikacji interakcji.
 */
interface PostgresAccessor
{
    public function updatePromotionStatistics(string $promotionName): void;

    public function updatePromotionDiscount(string $promotionName, float $newDiscount): void;
}
