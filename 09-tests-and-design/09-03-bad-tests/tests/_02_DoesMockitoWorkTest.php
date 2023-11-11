<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;

class _02_DoesMockitoWorkTest extends TestCase
{
    /**
     * W tym teście de facto weryfikujemy czy framework do mockowania działa.
     */
    public function test_should_return_positive_fraud_verification_when_fraud(): void
    {
        $another = $this->createMock(AnotherFraudService::class);
        $another->method('isFraud')->willReturn(true);

        self::assertTrue((new FraudService($another))->checkIfFraud(new Person()));
    }
}

class FraudService
{
    private AnotherFraudService $service;

    public function __construct(AnotherFraudService $service)
    {
        $this->service = $service;
    }

    public function checkIfFraud(Person $person): bool
    {
        return $this->service->isFraud($person);
    }
}

interface AnotherFraudService
{
    public function isFraud(Person $person): bool;
}

class Person
{
}
