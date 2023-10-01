<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Loan;

use SmartTesting\Db\PostgresAccessor;
use SmartTesting\Order\Promotion;

final class TestPostgresAccessor implements PostgresAccessor
{
    public function updatePromotionStatistics(string $promotionName): void
    {
        // do nothing
    }

    public function updatePromotionDiscount(string $promotionName, float $newDiscount): void
    {
        // do nothing
    }

    public function getValidPromotionsForDate(\DateTimeImmutable $localDate): array
    {
        return [
            new Promotion('test 10', 10),
            new Promotion('test 20', 20),
        ];
    }
}
