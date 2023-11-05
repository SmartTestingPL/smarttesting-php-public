<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Verification;

class CustomerVerifier
{
    /**
     * @var Verification[]
     */
    private array $verifications;

    public function __construct(iterable $verifications)
    {
        foreach ($verifications as $verification) {
            $this->verifications[] = $verification;
        }
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        $allMatch = true;
        foreach ($this->verifications as $verification) {
            if (!$verification->passes($customer->getPerson())) {
                $allMatch = false;
                break;
            }
        }

        if ($allMatch) {
            return CustomerVerificationResult::passed($customer->getUuid());
        }

        return CustomerVerificationResult::failed($customer->getUuid());
    }
}
