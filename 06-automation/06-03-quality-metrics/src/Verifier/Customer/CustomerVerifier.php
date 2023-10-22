<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Verification;

/**
 * Weryfikacja czy klient jest oszustem czy nie. Przechodzi po
 * różnych implementacjach weryfikacji i zwraca zagregowany wynik.
 *
 * Klasa używa obiektu-wrappera otaczającego metodę statyczną realizującą operacje bazodanowe.
 * Nie polecamy robienia czegoś takiego w metodzie statycznej, ale tu pokazujemy jak to obejść i przetestować
 * jeżeli z jakiegoś powodu nie da się tego zmienić (np. metoda statyczna jest dostarczana przez kogoś innego).
 */
class CustomerVerifier
{
    /**
     * @var Verification[]
     */
    private array $verifications;
    private BIKVerificationService $bikVerificationService;
    private VeryBadVerificationServiceWrapper $serviceWrapper;

    public function __construct(array $verifications, BIKVerificationService $bikVerificationService, VeryBadVerificationServiceWrapper $serviceWrapper)
    {
        $this->verifications = $verifications;
        $this->bikVerificationService = $bikVerificationService;
        $this->serviceWrapper = $serviceWrapper;
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        $externalResult = $this->bikVerificationService->verify($customer);
        $allMatch = true;
        foreach ($this->verifications as $verification) {
            if (!$verification->passes($customer->person())) {
                $allMatch = false;
                break;
            }
        }

        if ($allMatch && $externalResult->isPassed() && $this->serviceWrapper->verify()) {
            return CustomerVerificationResult::passed($customer->uuid());
        }

        return CustomerVerificationResult::failed($customer->uuid());
    }
}
