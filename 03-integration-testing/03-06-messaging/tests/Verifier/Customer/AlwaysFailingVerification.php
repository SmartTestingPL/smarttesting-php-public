<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

/**
 * Weryfikacja, która zawsze jest negatywna - klient chce nas oszukać.
 */
class AlwaysFailingVerification implements Verification
{
    public function passes(Person $person): bool
    {
        return false;
    }
}
