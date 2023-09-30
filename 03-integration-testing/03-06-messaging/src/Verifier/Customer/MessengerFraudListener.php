<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

class MessengerFraudListener implements FraudListener
{
    private VerificationRepository $repository;

    public function __construct(VerificationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function onFraud(CustomerVerification $verification): void
    {
        $this->repository->save(new VerifiedPerson(
            $verification->result()->userId(),
            $verification->person()->nationalIdentificationNumber(),
            $verification->result()->status()
        ));
    }
}
