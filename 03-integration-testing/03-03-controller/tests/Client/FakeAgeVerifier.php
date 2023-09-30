<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use SmartTesting\Client\Person;
use SmartTesting\Client\Verification;

class FakeAgeVerifier implements Verification
{
    public function passes(Person $person): bool
    {
        return $person->age() >= 50;
    }
}
