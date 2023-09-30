<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\Verification\AgeVerification;
use SmartTesting\Verifier\Customer\Verification\IdentificationNumberVerification;
use SmartTesting\Verifier\Customer\Verification\NameVerification;
use SmartTesting\Verifier\EventEmitter;
use SmartTesting\Verifier\VerificationEvent;
use Symfony\Component\Uid\Uuid;

/**
 * Klasa zawiera przykłady zastosowania mocka w celu weryfikacji interakcji z obiektem typu EventEmitter,
 * przykłady zastosowania buildera obiektów testowych, przykłady testowania komunikacji/interakcji.
 */
class CustomerVerifierTest extends CustomerTestBase
{
    private CustomerVerifier $verifier;
    private EventEmitter $eventEmmiter;
    private Customer $customer;

    protected function setUp(): void
    {
        $this->customer = $this->buildCustomer();
        $this->eventEmmiter = $this->createMock(EventEmitter::class);
        $this->verifier = new CustomerVerifier([
            new AgeVerification($this->eventEmmiter),
            new IdentificationNumberVerification($this->eventEmmiter),
            new NameVerification($this->eventEmmiter),
        ]);
    }

    /**
     * zastosowanie builder do setupowania.
     */
    public function testShouldVerifyCorrectPerson(): void
    {
        $customer = $this->builder()
            ->withNationalIdentificationNumber('80030818293')
            ->withDateOfBirth(\DateTimeImmutable::createFromFormat('Y-m-d', '1980-03-08'))
            ->withGender(Person::GENDER_MALE)
            ->build()
        ;

        $result = $this->verifier->verify($customer);

        self::assertTrue($result->isPassed());
        self::assertTrue($result->userId()->equals($customer->uuid()));
    }

    public function testShouldEmitVerificationEvent(): void
    {
        // Weryfikacja interakcji - sprawdzamy, że metoda emit(...) została wywołana 3 razy
        // z argumentem typu VerificationEvent, którego metoda passed(...) zwraca true
        $this->eventEmmiter->expects($this->exactly(3))->method('emit')->with(new VerificationEvent(true));

        $this->verifier->verify($this->customer);
    }

    private function buildCustomer(): Customer
    {
        return new Customer(Uuid::v4(), new Person(
                'John',
                'Smith',
                \DateTimeImmutable::createFromFormat('Y-m-d', '1996-08-28'),
                Person::GENDER_MALE,
                '96082812079')
        );
    }
}
