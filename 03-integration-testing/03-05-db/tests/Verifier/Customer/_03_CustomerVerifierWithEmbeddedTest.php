<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\DoctrineVerificationRepository;
use SmartTesting\Verifier\Customer\VerificationRepository;
use SmartTesting\Verifier\Customer\VerifiedPerson;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * Ten test wykorzystuje bazę danych typu embedded: sqlite
 * Przed jego uruchomieniem potrzebujemy uruchomić następujące polecenia.
 *
 * "bin/console d:d:c --env=embedded" - stworzenie bazy danych (konfiguracja w .env.embedded)
 * "bin/console d:m:m --no-interaction --env=embedded" - uruchomienie migracji dla sqlite
 *
 * Dodatkowo startowany jest cały kernel symfony, tak aby wstrzyknąć już gotowe repozytorium z połączeniem
 * do prawdziwej bazy danych (sqlite).
 */
class _03_CustomerVerifierWithEmbeddedTest extends KernelTestCase
{
    private VerificationRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel(['environment' => 'embedded']);
        $this->repository = self::$container->get(DoctrineVerificationRepository::class);
    }

    public function testShouldReturnStoredCustomerResultWhenCustomerAlreadyVerified(): void
    {
        $verifiedPerson = $this->givenAnExistingVerifiedPerson();

        // Przed uruchomieniem metody do przetestowania,
        // upewniamy się, że w bazie danych istnieje wpis dla danego użytkownika
        self::assertNotNull($this->repository->findByUserId($verifiedPerson->userId()));

        $result = $this->customerVerifierWithExceptionThrowingBik()
            ->verify(new Customer($verifiedPerson->userId(), $this->nonFraudPerson()));

        self::assertTrue($verifiedPerson->userId()->equals($result->userId()));
        self::assertTrue($result->isPassed());
    }

    public function testShouldCalculateCustomerResultWhenCustomerNotPreviouslyVerified(): void
    {
        $newPersonId = Uuid::v4();

        // Przed uruchomieniem metody do przetestowania,
        // upewniamy się, że w bazie danych NIE istnieje wpis dla danego użytkownika
        self::assertNull($this->repository->findByUserId($newPersonId));

        $result = $this->customerVerifierWithPassingBik()->verify(new Customer($newPersonId, $this->nonFraudPerson()));

        self::assertTrue($newPersonId->equals($result->userId()));
        self::assertTrue($result->isPassed());

        // Po uruchomieniu metody do przetestowania,
        // upewniamy się, że w bazie danych istnieje wpis dla danego użytkownika
        self::assertNotNull($this->repository->findByUserId($newPersonId));
    }

    private function verifiedNonFraud(): VerifiedPerson
    {
        return new VerifiedPerson(Uuid::v4(), '1234567890', CustomerVerificationResult::VERIFICATION_PASSED);
    }

    private function nonFraudPerson(): Person
    {
        return new Person('Ucziwy', 'Ucziwowski', new \DateTimeImmutable(), Person::GENDER_MALE, '1234567890');
    }

    private function givenAnExistingVerifiedPerson(): VerifiedPerson
    {
        $verifiedPerson = $this->verifiedNonFraud();
        $this->repository->save($verifiedPerson);

        return $verifiedPerson;
    }

    private function customerVerifierWithExceptionThrowingBik(): CustomerVerifier
    {
        return new CustomerVerifier([], new ExceptionThrowingBikVerifier(), $this->repository);
    }

    private function customerVerifierWithPassingBik(): CustomerVerifier
    {
        return new CustomerVerifier([], new AlwaysPassingBikVerifier(), $this->repository);
    }
}
