<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\EventEmitter;
use SmartTesting\Verifier\Verification;
use SmartTesting\Verifier\VerificationEvent;

/**
 * Weryfikacja wieku osoby wnioskującej o udzielenie pożyczki.
 */
class AgeVerification implements Verification
{
    private EventEmitter $eventEmitter;

    public function __construct(EventEmitter $eventEmitter)
    {
        $this->eventEmitter = $eventEmitter;
    }

    public function passes(Person $person): bool
    {
        if ($person->age() <= 0) {
            throw new \InvalidArgumentException('Age cannot be negative.');
        }

        $passes = $person->age() >= 18 && $person->age() <= 99;
        $this->eventEmitter->emit(new VerificationEvent($passes));

        return $passes;
    }
}
