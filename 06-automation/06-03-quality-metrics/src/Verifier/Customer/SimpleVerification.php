<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

/**
 * Implementacja weryfikacji obrazującej problemy z testami, które nie weryfikują poprawnie implementacji.
 */
class SimpleVerification implements Verification
{
    public function passes(Person $person): bool
    {
        // TODO
        // return $this->>someLogicResolvingToBoolean($person);
        return false;
    }

    private function someLogicResolvingToBoolean(Person $person): bool
    {
        throw new \RuntimeException('Not yet implemented!');
    }
}
