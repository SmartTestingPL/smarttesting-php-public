<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\VerificationEventPublisher;
use SmartTesting\Verifier\Verification;

/**
 * Weryfikacja wieku osoby wnioskującej o udzielenie pożyczki.
 */
class AgeVerification implements Verification
{
    private VerificationEventPublisher $eventPublisher;

    public function __construct(VerificationEventPublisher $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    public function passes(Person $person): bool
    {
        // Symuluje procesowanie w losowym czasie do 2 sekund
        usleep(random_int(1, 2000000));

        if ($person->age() <= 0) {
            throw new \InvalidArgumentException('Age cannot be negative.');
        }

        $this->eventPublisher->publish($this->name());

        return $person->age() >= 18 && $person->age() <= 99;
    }

    public function name(): string
    {
        return 'age';
    }
}
