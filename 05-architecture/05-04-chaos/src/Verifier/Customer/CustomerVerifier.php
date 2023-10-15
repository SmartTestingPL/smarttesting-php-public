<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Psr\Log\LoggerInterface;
use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Verification;
use Symfony\Component\Uid\Uuid;

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
     * Weryfikuje czy dana osoba jest oszustem.
     *
     * Najpierw łączymy się do bazy danych, żeby znaleźć daną osobę po id.
     * Jeśli dana osoba była już zapisana w bazie, to zwracamy zapisany rezultat,
     * w innym razie wykonujemy zapytanie do usługi zewnętrznej i również dokonujemy zapisu.
     *
     * Mamy tu problem w postaci braku obsługi jakichkolwiek wyjątków. Co jeśli baza danych
     * rzuci wyjątkiem? Jak powinniśmy to obsłużyć biznesowo?
     *
     * Po pierwszym uruchomieniu eksperymentu z dziedziny inżynierii chaosu, który wywali test
     * dot. bazy danych, zakomentuj linijkę
     *
     * $verifiedPerson = $this->repository->findByUserId($customer->uuid());
     *
     * i odkomentuj tę
     *
     * //$verifiedPerson = $this->findByUserId($customer->uuid());
     *
     * wówczas jeden z dwóch testów przejdzie, ponieważ obsługujemy poprawnie błędy bazodanowe.
     */
    public function verify(Customer $customer): CustomerVerificationResult
    {
        $verifiedPerson = $this->repository->findByUserId($customer->uuid());
        // $verifiedPerson = $this->findByUserId($customer->uuid());

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

    /**
     * Poprawiona wersja odpytania o wyniki z bazy danych. Jeśli wyjątek został rzucony,
     * zalogujemy go, ale z punktu widzenia biznesowego możemy spokojnie założyć, że
     * mamy do czynienia z potencjalnym oszustem.
     */
    private function findByUserId(Uuid $uuid): ?VerifiedPerson
    {
        try {
            return $this->repository->findByUserId($uuid);
        } catch (\Throwable $exception) {
            $this->logger->error('Exception occurred while trying to fetch results from DB. Will assume fraud', ['exception' => $exception]);

            return new VerifiedPerson($uuid, '', 'failed');
        }
    }
}
