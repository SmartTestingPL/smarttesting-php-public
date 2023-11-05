<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

class VerificationTask implements Task
{
    private Verification $verification;
    private Person $person;

    public function __construct(Verification $verification, Person $person)
    {
        $this->verification = $verification;
        $this->person = $person;
    }

    public function run(Environment $environment): mixed
    {
        return new VerificationResult(
            $this->verification->name(),
            $this->verification->passes($this->person)
        );
    }
}
