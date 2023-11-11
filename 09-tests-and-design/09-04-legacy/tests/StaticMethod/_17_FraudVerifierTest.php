<?php

declare(strict_types=1);

namespace SmartTesting\Tests\StaticMethod;

use PHPUnit\Framework\TestCase;

class _17_FraudVerifierTest extends TestCase
{
    /**
     * Test się wywala, gdyż wywołanie `isFraud` wywoła połączenie do bazy danych.
     * Nie wierzysz? Zakomentuj `markTestSkipped` i sprawdź sam!
     */
    public function test_should_mark_client_with_debt_as_fraud(): void
    {
        $this->markTestSkipped();

        $verifier = new _18_FraudVerifier();
        self::assertTrue($verifier->isFraud('Fraudowski'));
    }

    /**
     * Test wykorzystujący możliwość podmiany globalnej, statycznej instancji produkcyjnej
     * na wersję testową, która zwraca wartość ustawioną na sztywno.
     */
    public function test_should_mark_client_with_debt_as_fraud_with_static(): void
    {
        _20_DatabaseAccessorImplWithSetter::setInstance(new _21_FakeDatabaseAccessor());

        $verifier = new FraudVerifierForSetter();
        self::assertTrue($verifier->isFraud('Fraudowski'));
    }

    /**
     * Ważne, żeby po sobie posprzątać!
     */
    protected function tearDown(): void
    {
        _20_DatabaseAccessorImplWithSetter::reset();
    }
}

/**
 * Przykład implementacji wołającej singleton {@link _19_DatabaseAccessorImpl}.
 */
class _18_FraudVerifier
{
    public function isFraud(string $name): bool
    {
        return _19_DatabaseAccessorImpl::getInstance()->getClientByName($name)->hasDebt();
    }
}

class FraudVerifierForSetter
{
    public function isFraud(string $name): bool
    {
        return _20_DatabaseAccessorImplWithSetter::getInstance()->getClientByName($name)->hasDebt();
    }
}

class _19_DatabaseAccessorImpl
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getClientByName(string $name): Client
    {
        throw new \RuntimeException('Can\'t connect to the database');
    }
}

class _20_DatabaseAccessorImplWithSetter
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function setInstance(self $instance): void
    {
        self::$instance = $instance;
    }

    public static function reset(): void
    {
        self::$instance = new self();
    }

    public function getClientByName(string $name): Client
    {
        throw new \RuntimeException('Can\'t connect to the database');
    }
}

class _21_FakeDatabaseAccessor extends _20_DatabaseAccessorImplWithSetter
{
    public function __construct()
    {
    }

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
