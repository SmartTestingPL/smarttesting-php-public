<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

class VerificationResult
{
    private string $name;
    private bool $result;

    public function __construct(string $name, bool $result)
    {
        $this->name = $name;
        $this->result = $result;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function result(): bool
    {
        return $this->result;
    }
}
