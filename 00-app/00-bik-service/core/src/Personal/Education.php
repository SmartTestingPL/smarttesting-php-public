<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Personal;

final class Education
{
    private const NONE = 'none';
    private const BASIC = 'basic';
    private const MEDIUM = 'medium';
    private const HIGH = 'high';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function none(): self
    {
        return new self(self::NONE);
    }

    public static function basic(): self
    {
        return new self(self::BASIC);
    }

    public static function medium(): self
    {
        return new self(self::MEDIUM);
    }

    public static function high(): self
    {
        return new self(self::HIGH);
    }

    public static function fromString(string $value): self
    {
        if (!in_array($value, [self::NONE, self::BASIC, self::MEDIUM, self::HIGH], true)) {
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
