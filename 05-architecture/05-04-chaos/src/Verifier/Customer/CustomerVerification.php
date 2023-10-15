<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use SmartTesting\Customer\Person;

class CustomerVerification
{
    private Person $person;

    private CustomerVerificationResult $result;

    public function __construct(Person $person, CustomerVerificationResult $result)
    {
        $this->person = $person;
        $this->result = $result;
    }

    public function person(): Person
    {
        return $this->person;
    }

    public function result(): CustomerVerificationResult
    {
        return $this->result;
    }
}
