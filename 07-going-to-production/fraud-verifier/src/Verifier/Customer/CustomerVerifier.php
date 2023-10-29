<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Flagception\Manager\FeatureManagerInterface;
use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Customer\Verification\NewTypeOfVerification;
use SmartTesting\Verifier\Verification;

class CustomerVerifier
{
    /**
     * @var Verification[]
     */
    private array $verifications;

    private FeatureManagerInterface $featureManager;

    public function __construct(iterable $verifications, FeatureManagerInterface $featureManager)
    {
        foreach ($verifications as $verification) {
            $this->verifications[] = $verification;
        }
        $this->featureManager = $featureManager;
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        $verifications = $this->verifications;

        if ($this->featureManager->isActive('new_verification')) {
            $verifications[] = new NewTypeOfVerification();
        }

        $allMatch = true;
        foreach ($verifications as $verification) {
            if (!$verification->passes($customer->person())) {
                $allMatch = false;
                break;
            }
        }

        if ($allMatch) {
            return CustomerVerificationResult::passed($customer->uuid());
        }

        return CustomerVerificationResult::failed($customer->uuid());
    }
}
