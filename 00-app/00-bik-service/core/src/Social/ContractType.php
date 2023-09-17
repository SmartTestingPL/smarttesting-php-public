<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Social;

final class ContractType
{
    private const EMPLOYMENT_CONTRACT = 'employment-contract';
    private const OWN_BUSINESS_ACTIVITY = 'own-business-activity';
    private const UNEMPLOYED = 'unemployed';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function employmentContract(): self
    {
        return new self(self::EMPLOYMENT_CONTRACT);
    }

    public static function ownBusinessActivity(): self
    {
        return new self(self::OWN_BUSINESS_ACTIVITY);
    }

    public static function unemployed(): self
    {
        return new self(self::UNEMPLOYED);
    }

    public static function fromString(string $value): self
    {
        if (!in_array($value, [self::EMPLOYMENT_CONTRACT, self::OWN_BUSINESS_ACTIVITY, self::UNEMPLOYED], true)) {
            throw new \InvalidArgumentException();
        }

        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
