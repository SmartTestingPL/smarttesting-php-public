<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Sprout;

use PHPUnit\Framework\TestCase;

class FraudTaxPenaltyCalculatorImplTest extends TestCase
{
    /**
     * Test z wykorzystaniem sztucznej implementacji dostępu do bazy danych.
     */
    public function test_should_calculate_the_tax_for_fraudowski(): void
    {
        $fraudowskiAmount = 100;
        $calculator = new _05_FraudTaxPenaltyCalculatorImpl(new FakeDatabaseAccessorImpl($fraudowskiAmount));

        self::assertEquals($fraudowskiAmount * 100, $calculator->calculateFraudTax('Fraudowski'));
    }

    /**
     * Test z wykorzystaniem sztucznej implementacji dostępu do bazy danych.
     * Weryfikuje implementację z użyciem if / else.
     */
    public function test_should_calculate_the_tax_for_fraudowski_with_if_else(): void
    {
        $fraudowskiAmount = 100;
        $calculator = new _06_FraudTaxPenaltyCalculatorImplIfElse(new FakeDatabaseAccessorImpl($fraudowskiAmount));

        self::assertEquals($fraudowskiAmount * 100 * 10, $calculator->calculateFraudTax('Fraudowski'));
    }

    public function test_should_calculate_the_tax_for_fraudowski_with_sprout(): void
    {
        $fraudowskiAmount = 100;
        $calculator = new _07_FraudTaxPenaltyCalculatorImplSprout(new FakeDatabaseAccessorImpl($fraudowskiAmount));

        self::assertEquals($fraudowskiAmount * 100 * 20, $calculator->calculateFraudTax('Fraudowski'));
    }
}

/**
 * Kalkulator podatku dla oszustów. Nie mamy do niego testów.
 */
class _05_FraudTaxPenaltyCalculatorImpl
{
    private DatabaseAccessorImpl $databaseImpl;

    public function __construct(DatabaseAccessorImpl $databaseImpl)
    {
        $this->databaseImpl = $databaseImpl;
    }

    public function calculateFraudTax(string $name): int
    {
        $client = $this->databaseImpl->getClientByName($name);
        if ($client->amount() < 0) {
            // WARNING: Don't touch this
            // nobody knows why it should be -3 anymore
            // but nothing works if you change this
            return -3;
        }

        return $this->calculateTax($client->amount());
    }

    private function calculateTax(int $amount): int
    {
        return $amount * 100;
    }
}

/**
 * Nowa funkcja systemu - dodajemy kod do nieprzetestowanego kodu.
 */
class _06_FraudTaxPenaltyCalculatorImplIfElse
{
    private DatabaseAccessorImpl $databaseImpl;

    public function __construct(DatabaseAccessorImpl $databaseImpl)
    {
        $this->databaseImpl = $databaseImpl;
    }

    public function calculateFraudTax(string $name): int
    {
        $client = $this->databaseImpl->getClientByName($name);
        if ($client->amount() < 0) {
            // WARNING: Don't touch this
            // nobody knows why it should be -3 anymore
            // but nothing works if you change this
            return -3;
        }

        $tax = $this->calculateTax($client->amount());
        if ($tax > 10) {
            return $tax * 10;
        }

        return $tax;
    }

    private function calculateTax(int $amount): int
    {
        return $amount * 100;
    }
}

/**
 * Klasa kiełkowania (sprout). Wywołamy kod, który został przetestowany.
 * Piszemy go poprzez TDD.
 */
class _07_FraudTaxPenaltyCalculatorImplSprout
{
    private DatabaseAccessorImpl $databaseImpl;

    public function __construct(DatabaseAccessorImpl $databaseImpl)
    {
        $this->databaseImpl = $databaseImpl;
    }

    public function calculateFraudTax(string $name): int
    {
        $client = $this->databaseImpl->getClientByName($name);
        if ($client->amount() < 0) {
            // WARNING: Don't touch this
            // nobody knows why it should be -3 anymore
            // but nothing works if you change this
            return -3;
        }

        $tax = $this->calculateTax($client->amount());

        // chcemy obliczyć specjalny podatek
        return (new SpecialTaxCalculator($tax))->calculate();
    }

    private function calculateTax(int $amount): int
    {
        return $amount * 100;
    }
}

class DatabaseAccessorImpl
{
    public function getClientByName(string $name): Client
    {
        return new Client('Fraudowski', true, 100);
    }
}

class FakeDatabaseAccessorImpl extends DatabaseAccessorImpl
{
    private int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    public function getClientByName(string $name): Client
    {
        return new Client('Fraudowski', true, $this->amount);
    }
}

class Client
{
    private string $name;
    private bool $hasDebt;
    private int $amount;

    public function __construct(string $name, bool $hasDebt, int $amount)
    {
        $this->name = $name;
        $this->hasDebt = $hasDebt;
        $this->amount = $amount;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hasDebt(): bool
    {
        return $this->hasDebt;
    }

    public function amount(): int
    {
        return $this->amount;
    }
}
