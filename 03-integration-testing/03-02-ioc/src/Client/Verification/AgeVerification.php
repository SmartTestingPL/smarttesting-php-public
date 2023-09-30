<?php

declare(strict_types=1);

namespace SmartTesting\Client\Verification;

use SmartTesting\Client\DatabaseAccessor;
use SmartTesting\Client\HttpCallMaker;
use SmartTesting\Client\Verification;

/**
 * Weryfikacja po wieku.
 *
 * Na potrzeby scenariusza lekcji, brak prawdziwej implementacji.
 * Klasa symuluje połączenie po HTTP i po bazie danych.
 */
class AgeVerification implements Verification
{
    private HttpCallMaker $maker;
    private DatabaseAccessor $accessor;

    public function __construct(HttpCallMaker $maker, DatabaseAccessor $accessor)
    {
        $this->maker = $maker;
        $this->accessor = $accessor;
    }

    public function passes(): bool
    {
        return false;
    }
}
