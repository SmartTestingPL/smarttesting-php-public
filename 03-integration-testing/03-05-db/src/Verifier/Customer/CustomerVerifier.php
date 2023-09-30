<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Verification;

/**
 * Weryfikacja czy klient jest oszustem czy nie. Przechodzi po
 * różnych implementacjach weryfikacji i zapisuje jej wynik w bazie danych.
 * Jeśli, przy którejś okaże się, że użytkownik jest oszustem, wówczas
 * odpowiedni rezultat zostanie zwrócony.
 */
class CustomerVerifier
{
    /**
     * @var Verification[]
     */
    private array $verifications;

    private BIKVerificationService $bikService;

    private VerificationRepository $repository;

    public function __construct(array $verifications, BIKVerificationService $bikService, VerificationRepository $repository)
    {
        $this->verifications = $verifications;
        $this->bikService = $bikService;
        $this->repository = $repository;
    }

    /**
     * Główna metoda biznesowa. Sprawdza, czy już nie doszło do weryfikacji klienta i jeśli
     * rezultat zostanie odnaleziony w bazie danych to go zwraca. W innym przypadku zapisuje
     * wynik weryfikacji w bazie danych. Weryfikacja wówczas zachodzi poprzez odpytanie
     * BIKu o stan naszego klienta.
     */
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

        $passed = $allMatch && $this->bikService->verify($customer)->isPassed();
        $this->repository->save(new VerifiedPerson(
            $customer->uuid(),
            $customer->person()->nationalIdentificationNumber(),
            $passed ? CustomerVerificationResult::VERIFICATION_PASSED : CustomerVerificationResult::VERIFICATION_FAILED
        ));

        if ($passed) {
            return CustomerVerificationResult::passed($customer->uuid());
        }

        return CustomerVerificationResult::failed($customer->uuid());
    }
}
