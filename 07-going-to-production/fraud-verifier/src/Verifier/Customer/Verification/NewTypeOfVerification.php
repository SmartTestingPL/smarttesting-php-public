<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

/**
 * Nowy typ weryfikacja. W celu demonstracyjnym będzie on zawsze zwracał false (status weryfikacja będzie negatywny).
 */
class NewTypeOfVerification implements Verification
{
    public function passes(Person $person): bool
    {
        return false;
    }
}
