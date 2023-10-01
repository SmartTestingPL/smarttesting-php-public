<?php

declare(strict_types=1);

namespace SmartTesting\Client;

/**
 * Weryfikacja po wieku. Osoba w odpowiednim wieku zostanie
 * zweryfikowana pozytywnie.
 */
class AgeVerification implements Verification
{
    public function passes(Person $person): bool
    {
        if ($person->age() < 0) {
            throw new \InvalidArgumentException('Age cannot be negative.');
        }

        return $person->age() >= 18 && $person->age() <= 99;
    }
}
