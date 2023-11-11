<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;

class _01_AcceptanceTest extends TestCase
{
    public function test_should_verify_a_client_with_debt_as_fraud(): void
    {
        $this->markTestSkipped();

        $fraud = $this->clientWithDebt();

        $verification = $this->verifyClient($fraud);

        $this->thenIsVerifiedAsFraud($verification);
    }

    private function clientWithDebt(): array
    {
        return [];
    }

    private function verifyClient(array $client)
    {
        return null;
    }

    private function thenIsVerifiedAsFraud($verification): void
    {
        self::assertNotNull($verification);
    }
}
