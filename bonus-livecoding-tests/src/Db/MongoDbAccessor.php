<?php

declare(strict_types=1);

namespace SmartTesting\Db;

/**
 * Interfejs służący do komunikacji z dokumentową bazą danych.
 * Posłuży nam do przykładów zastosowania stubów.
 */
interface MongoDbAccessor
{
    public function getPromotionDiscount(string $promotionName): float;

    public function getMinCommission(): float;
}
