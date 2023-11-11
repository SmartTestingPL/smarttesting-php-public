<?php

declare(strict_types=1);

namespace App\Verifier;

class VerificationResult implements \JsonSerializable
{
    private const FRAUD = 'fraud';
    private const NOT_FRAUD = 'not-fraud';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function FRAUD(): self
    {
        return new self(self::FRAUD);
    }

    public static function NOT_FRAUD(): self
    {
        return new self(self::NOT_FRAUD);
    }

    public function jsonSerialize(): array
    {
        return ['status' => $this->value];
    }
}
