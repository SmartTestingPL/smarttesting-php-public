<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

class _01_NameVerification implements Verification
{
    public function passes(Person $person): bool
    {
        fwrite(STDERR, sprintf('Person gender is [%s]'.PHP_EOL, $person->gender()));
        if ($person->name() === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        return mb_strlen($person->name()) > 0;
    }
}
