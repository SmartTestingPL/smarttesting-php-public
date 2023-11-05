<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

class NameVerification implements Verification
{
    public function passes(Person $person): bool
    {
        return ctype_alpha($person->getName());
    }
}
