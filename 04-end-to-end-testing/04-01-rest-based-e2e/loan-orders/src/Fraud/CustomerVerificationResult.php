<?php

declare(strict_types=1);

namespace SmartTesting\Fraud;

use Symfony\Component\Uid\Uuid;

/**
 * Rezultat weryfikacji klienta.
 */
class CustomerVerificationResult
{
    public const VERIFICATION_PASSED = 'passed';
    public const VERIFICATION_FAILED = 'failed';

    private Uuid $userId;
    private string $status;

    private function __construct(Uuid $userId, string $status)
    {
        $this->userId = $userId;
        $this->status = $status;
    }

    public static function passed(Uuid $userId): self
    {
        return new self($userId, self::VERIFICATION_PASSED);
    }

    public static function failed(Uuid $userId): self
    {
        return new self($userId, self::VERIFICATION_FAILED);
    }

    public function userId(): Uuid
    {
        return $this->userId;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function isPassed(): bool
    {
        return $this->status === self::VERIFICATION_PASSED;
    }
}
