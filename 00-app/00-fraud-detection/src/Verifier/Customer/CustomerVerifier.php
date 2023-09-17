<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Psr\Log\LoggerInterface;
use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Verification;

/**
 * Weryfikacja czy klient jest oszustem czy nie. Przechodzi po
 * różnych implementacjach weryfikacji i jeśli, przy którejś okaże się,
 * że użytkownik jest oszustem, wówczas odpowiedni rezultat zostanie zwrócony.
 */
class CustomerVerifier
{
    /**
     * @var Verification[]
     */
    private array $verifications = [];

    private VerificationRepository $repository;

    private LoggerInterface $logger;

    public function __construct(iterable $verifications, VerificationRepository $repository, LoggerInterface $logger)
    {
        foreach ($verifications as $verification) {
            $this->verifications[] = $verification;
        }
        $this->repository = $repository;
        $this->logger = $logger;
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

        $this->repository->save(new VerifiedPerson(
            $customer->uuid(),
            $customer->person()->nationalIdentificationNumber(),
            $allMatch ? CustomerVerificationResult::VERIFICATION_PASSED : CustomerVerificationResult::VERIFICATION_FAILED
        ));

        if ($allMatch) {
            return CustomerVerificationResult::passed($customer->uuid());
        }

        return CustomerVerificationResult::failed($customer->uuid());
    }
}
