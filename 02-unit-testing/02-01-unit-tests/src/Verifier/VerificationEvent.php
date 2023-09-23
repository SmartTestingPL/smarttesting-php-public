<?php

declare(strict_types=1);

namespace SmartTesting\Verifier;

/**
 * Zdarzenie związane z weryfikacją klienta.
 */
class VerificationEvent
{
    private bool $passed;

    public function __construct(bool $passed)
    {
        $this->passed = $passed;
    }

    public function passed(): bool
    {
        return $this->passed;
    }
}
