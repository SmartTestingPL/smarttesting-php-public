<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\EventEmitter;
use SmartTesting\Verifier\Verification;
use SmartTesting\Verifier\VerificationEvent;

class NameVerification implements Verification
{
    private EventEmitter $eventEmitter;

    public function __construct(EventEmitter $eventEmitter)
    {
        $this->eventEmitter = $eventEmitter;
    }

    public function passes(Person $person): bool
    {
        $passes = ctype_alpha($person->name());
        $this->eventEmitter->emit(new VerificationEvent($passes));

        return $passes;
    }
}
