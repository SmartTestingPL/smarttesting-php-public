<?php

declare(strict_types=1);

namespace SmartTesting\Tests\E2e;

use function PHPUnit\Framework\assertEquals;

/**
 * Przykład zastosowania wzorca AssertObject.
 */
class LoanOrderAssert
{
    private array $loadOrder;

    public function __construct(array $loadOrder)
    {
        $this->loadOrder = $loadOrder;
    }

    public function customerVerificationPassed(): void
    {
        assertEquals('verified', $this->loadOrder['status']);
    }

    public function customerVerificationFailed(): void
    {
        assertEquals('rejected', $this->loadOrder['status']);
    }
}
