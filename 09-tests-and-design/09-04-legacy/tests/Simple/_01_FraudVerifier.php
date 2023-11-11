<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Simple;

class _01_FraudVerifier
{
    private DatabaseAccessorImpl $impl;

    public function __construct(DatabaseAccessorImpl $impl)
    {
        $this->impl = $impl;
    }

    public function isFraud(string $name): bool
    {
        return $this->impl->getClientByName($name)->hasDebt();
    }
}

class DatabaseAccessorImpl
{
    public function getClientByName(string $name): Client
    {
        return new Client();
    }
}

class Client
{
    private bool $hasDebt;

    public function hasDebt(): bool
    {
        return $this->hasDebt;
    }
}
