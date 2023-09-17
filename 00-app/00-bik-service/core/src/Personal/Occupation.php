<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Personal;

final class Occupation
{
    private const PROGRAMMER = 'programmer';
    private const LAWYER = 'lawyer';
    private const DOCTOR = 'doctor';
    private const OTHER = 'other';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function programmer(): self
    {
        return new self(self::PROGRAMMER);
    }

    public static function laywer(): self
    {
        return new self(self::LAWYER);
    }

    public static function doctor(): self
    {
        return new self(self::DOCTOR);
    }

    public static function other(): self
    {
        return new self(self::OTHER);
    }

    public static function fromString(string $value): self
    {
        if (!in_array($value, [self::PROGRAMMER, self::LAWYER, self::DOCTOR, self::OTHER], true)) {
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
