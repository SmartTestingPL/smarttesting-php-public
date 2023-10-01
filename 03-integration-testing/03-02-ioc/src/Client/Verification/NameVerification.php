<?php

declare(strict_types=1);

namespace SmartTesting\Client\Verification;

use SmartTesting\Client\EventEmitter;
use SmartTesting\Client\Verification;

/**
 * Weryfikacja po nazwisku.
 *
 * Na potrzeby scenariusza lekcji, brak prawdziwej implementacji.
 * Klasa symuluje połączenie po brokerze wiadomości.
 */
class NameVerification implements Verification
{
    private EventEmitter $eventEmitter;

    public function __construct(EventEmitter $eventEmitter)
    {
        $this->eventEmitter = $eventEmitter;
    }

    public function passes(): bool
    {
        return false;
    }
}
