<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

/**
 * Weryfikacja wieku osoby wnioskującej o udzielenie pożyczki.
 */
class AgeVerification implements Verification
{
    public function passes(Person $person): bool
    {
        if ($person->age() <= 0) {
            throw new \InvalidArgumentException('Age cannot be negative.');
        }

        return $person->age() >= 18 && $person->age() <= 99;
    }
}
