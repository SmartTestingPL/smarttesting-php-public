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
    private array $verifications = [];

    private VerificationRepository $repository;

    private FraudAlertNotifier $fraudAlertNotifier;

    public function __construct(iterable $verifications, VerificationRepository $repository, FraudAlertNotifier $fraudAlertNotifier)
    {
        foreach ($verifications as $verification) {
            $this->verifications[] = $verification;
        }
        $this->repository = $repository;
        $this->fraudAlertNotifier = $fraudAlertNotifier;
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        $verifiedPerson = $this->repository->findByUserId($customer->uuid());
        if ($verifiedPerson !== null) {
            return CustomerVerificationResult::{$verifiedPerson->status()}($verifiedPerson->userId());
        }

        $allMatch = true;
        foreach ($this->verifications as $verification) {
            if (!$verification->passes($customer->person())) {
                $allMatch = false;
                break;
            }
        }

        $this->repository->save(new VerifiedPerson(
            $customer->uuid(),
            $customer->person()->nationalIdentificationNumber(),
            $allMatch ? CustomerVerificationResult::VERIFICATION_PASSED : CustomerVerificationResult::VERIFICATION_FAILED
        ));

        if ($allMatch) {
            return CustomerVerificationResult::passed($customer->uuid());
        }

        $result = CustomerVerificationResult::failed($customer->uuid());
        $this->fraudAlertNotifier->fraudFound(new CustomerVerification($customer->person(), $result));

        return $result;
    }
}
