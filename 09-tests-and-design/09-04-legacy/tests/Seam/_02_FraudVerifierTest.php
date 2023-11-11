<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Seam;

use PHPUnit\Framework\TestCase;

class _02_FraudVerifierTest extends TestCase
{
    /**
     * Przykład próby napisania testu do istniejącej klasy łączącej się z bazą danych.
     */
    public function test_should_mark_client_with_debt_as_fraud(): void
    {
        $this->markTestSkipped();

        $accessor = new _03_DatabaseAccessorImpl();
        $verifier = new FraudVerifier($accessor);

        self::assertTrue($verifier->isFraud('Fraudowski'));
    }

    /**
     * Przykład testu z wykorzystaniem szwa (seam).
     */
    public function test_should_mark_client_with_debt_as_fraud_with_seam(): void
    {
        $accessor = new _04_FakeDatabaseAccessor();
        $verifier = new FraudVerifier($accessor);

        self::assertTrue($verifier->isFraud('Fraudowski'));
    }

    public function test_should_mark_client_with_debt_as_fraud_with_seam_logic_in_constructor(): void
    {
        $this->markTestSkipped();

        $verifier = new _09_FraudVerifierLogicInConstructor();

        self::assertTrue($verifier->isFraud('Fraudowski'));
    }

    public function test_should_mark_client_with_debt_as_fraud_with_a_mock(): void
    {
        $accessor = $this->createMock(_10_DatabaseAccessorImplWithLogicInTheConstructor::class);
        $accessor->expects($this->once())->method('getClientByName')->willReturn(new Client('Fraudowski', true));

        $verifier = new _11_FraudVerifierLogicInConstructorExtractLogic($accessor);

        self::assertTrue($verifier->isFraud('Fraudowski'));
    }

    public function test_should_mark_client_with_debt_as_fraud_with_an_extracted_interface(): void
    {
        $verifier = new _13_FraudVerifierWithInterface(new _14_FakeDatabaseAccessorWithInterface());

        self::assertTrue($verifier->isFraud('Fraudowski'));
    }
}

class _03_DatabaseAccessorImpl
{
    public function getClientByName(string $name): Client
    {
        throw new \RuntimeException('Can\'t connect to the database');
    }
}

class _04_FakeDatabaseAccessor extends _03_DatabaseAccessorImpl
{
    /**
     * Nasz szew (seam)! Nadpisujemy problematyczną metodę bez zmiany kodu produkcyjnego.
     */
    public function getClientByName(string $name): Client
    {
        return new Client('Fraudowski', true);
    }
}

class _10_DatabaseAccessorImplWithLogicInTheConstructor
{
    public function __construct()
    {
        throw new \RuntimeException('Can\'t connect to the database');
    }

    public function getClientByName(string $name): Client
    {
        return new Client('Fraudowski', true);
    }
}

class FraudVerifier
{
    private _03_DatabaseAccessorImpl $impl;

    public function __construct(_03_DatabaseAccessorImpl $impl)
    {
        $this->impl = $impl;
    }

    public function isFraud(string $name): bool
    {
        return $this->impl->getClientByName($name)->hasDebt();
    }
}

class _09_FraudVerifierLogicInConstructor
{
    private _10_DatabaseAccessorImplWithLogicInTheConstructor $impl;

    public function __construct()
    {
        $this->impl = new _10_DatabaseAccessorImplWithLogicInTheConstructor();
    }

    public function isFraud(string $name): bool
    {
        return $this->impl->getClientByName($name)->hasDebt();
    }
}

class _11_FraudVerifierLogicInConstructorExtractLogic
{
    private _10_DatabaseAccessorImplWithLogicInTheConstructor $impl;

    public function __construct(_10_DatabaseAccessorImplWithLogicInTheConstructor $impl)
    {
        $this->impl = $impl;
    }

    public function isFraud(string $name): bool
    {
        return $this->impl->getClientByName($name)->hasDebt();
    }
}

class _13_FraudVerifierWithInterface
{
    private _12_DatabaseAccessor $accessor;

    public function __construct(_12_DatabaseAccessor $accessor)
    {
        $this->accessor = $accessor;
    }

    public function isFraud(string $name): bool
    {
        return $this->accessor->getClientByName($name)->hasDebt();
    }
}

interface _12_DatabaseAccessor
{
    public function getClientByName(string $name): Client;
}

class _14_FakeDatabaseAccessorWithInterface implements _12_DatabaseAccessor
{
    public function getClientByName(string $name): Client
    {
        return new Client('Fraudowski', true);
    }
}

class Client
{
    private string $name;
    private bool $hasDebt;

    public function __construct(string $name, bool $hasDebt)
    {
        $this->name = $name;
        $this->hasDebt = $hasDebt;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hasDebt(): bool
    {
        return $this->hasDebt;
    }
}
