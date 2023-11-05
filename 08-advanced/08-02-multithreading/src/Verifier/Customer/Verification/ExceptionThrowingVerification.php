<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

class ExceptionThrowingVerification implements Verification
{
    public function passes(Person $person): bool
    {
        throw new \RuntimeException('something goes wrong');
    }

    public function name(): string
    {
        return 'exception';
    }
}
