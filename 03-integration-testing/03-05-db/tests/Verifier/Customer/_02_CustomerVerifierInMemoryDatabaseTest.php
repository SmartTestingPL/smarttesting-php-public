<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\VerificationRepository;
use SmartTesting\Verifier\Customer\VerifiedPerson;
use Symfony\Component\Uid\Uuid;

/**
 * Klasa testowa pokazująca jak testując serwis aplikacyjny `CustomerVerifier`, możemy użyć bazy
 * danych w pamięci.
 */
class _02_CustomerVerifierInMemoryDatabaseTest extends TestCase
{
    private VerificationRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new _02_InMemoryVerificationRepository();
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
