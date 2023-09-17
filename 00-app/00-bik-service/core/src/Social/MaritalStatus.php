<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Social;

final class MaritalStatus
{
    private const SINGLE = 'single';
    private const MARRIED = 'married';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function single(): self
    {
        return new self(self::SINGLE);
    }

    public static function married(): self
    {
        return new self(self::MARRIED);
    }

    public static function fromString(string $value): self
    {
        if (!in_array($value, [self::SINGLE, self::MARRIED], true)) {
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
