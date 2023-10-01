<?php

declare(strict_types=1);

namespace SmartTesting\Verifier;

use SmartTesting\Customer\Person;

interface Verification
{
    public function passes(Person $person): bool;
}
