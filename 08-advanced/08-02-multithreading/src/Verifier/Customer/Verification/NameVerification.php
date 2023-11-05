<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\VerificationEventPublisher;
use SmartTesting\Verifier\Verification;

class NameVerification implements Verification
{
    private VerificationEventPublisher $eventPublisher;

    public function __construct(VerificationEventPublisher $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    public function passes(Person $person): bool
    {
        // Symuluje procesowanie w losowym czasie do 2 sekund
        usleep(random_int(1, 2000000));

        $this->eventPublisher->publish($this->name());

        return ctype_alpha($person->name());
    }

    public function name(): string
    {
        return 'name';
    }
}
