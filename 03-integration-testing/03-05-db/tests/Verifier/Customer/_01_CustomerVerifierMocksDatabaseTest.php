<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\VerificationRepository;
use SmartTesting\Verifier\Customer\VerifiedPerson;
use Symfony\Component\Uid\Uuid;

/**
 * Klasa testowa pokazująca jak testując serwis aplikacyjny `CustomerVerifier`,
 * możemy zamockować komunikację z bazą danych.
 */
class _01_CustomerVerifierMocksDatabaseTest extends TestCase
{
    /**
     * @var VerificationRepository|MockObject
     */
    private VerificationRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(VerificationRepository::class);
    }

    /**
     * W przypadku ówczesnego zapisu klienta w bazie danych, chcemy się upewnić, że
     * nie dojdzie do ponownego zapisu klienta w bazie danych.
     */
    public function testShouldReturnStoredCustomerResultWhenCustomerAlreadyVerified(): void
    {
        // No storing to the database took place
        $this->repository->expects($this->never())->method('save');

        $verifiedPerson = $this->givenAnExistingVerifiedPerson();
        $result = $this->customerVerifierWithExceptionThrowingBik()
            ->verify(new Customer($verifiedPerson->userId(), $this->nonFraudPerson()));

        self::assertTrue($verifiedPerson->userId()->equals($result->userId()));
        self::assertTrue($result->isPassed());
    }

    /**
     * W przypadku braku zapisu klienta w bazie danych, chcemy się upewnić, że
     * dojdzie do zapisu w bazie danych.
     */
    public function testShouldCalculateCustomerResultWhenCustomerNotPreviouslyVerified(): void
    {
        $newPersonId = Uuid::v4();

        // chcemy się upewnić, że doszło do zapisu w bazie danych
        $this->repository->expects($this->once())->method('save');
        $result = $this->customerVerifierWithPassingBik()->verify(new Customer($newPersonId, $this->nonFraudPerson()));

        self::assertTrue($newPersonId->equals($result->userId()));
        self::assertTrue($result->isPassed());
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
        // Symulujemy, że osoba została zapisana w bazie danych wcześniej
        $this->repository->method('findByUserId')->with($verifiedPerson->userId())
            ->willReturn($verifiedPerson);

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
