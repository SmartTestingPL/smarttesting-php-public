<?php

declare(strict_types=1);

namespace SmartTesting\Client\Verification;

use SmartTesting\Client\DatabaseAccessor;
use SmartTesting\Client\Verification;

/**
 * Weryfikacja po numerze pesel.
 *
 * Na potrzeby scenariusza lekcji, brak prawdziwej implementacji.
 * Klasa symuluje połączenie po bazie danych.
 */
class IdentificationNumberVerification implements Verification
{
    private DatabaseAccessor $databaseAccessor;

    public function __construct(DatabaseAccessor $databaseAccessor)
    {
        $this->databaseAccessor = $databaseAccessor;
    }

    public function passes(): bool
    {
        return false;
    }
}
