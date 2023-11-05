<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\Verification\AgeVerification;
use SmartTesting\Verifier\Customer\Verification\ExceptionThrowingVerification;
use SmartTesting\Verifier\Customer\Verification\IdentificationNumberVerification;
use SmartTesting\Verifier\Customer\Verification\NameVerification;
use SmartTesting\Verifier\Customer\VerificationEventPublisher;
use SmartTesting\Verifier\Customer\VerificationResult;
use Symfony\Component\Uid\Uuid;

class _02_CustomerVerifierExceptionTest extends TestCase
{
    private CustomerVerifier $verifier;

    protected function setUp(): void
    {
        $publisher = new VerificationEventPublisher();

        $this->verifier = new CustomerVerifier([
            new AgeVerification($publisher),
            new IdentificationNumberVerification($publisher),
            new NameVerification($publisher),
            new ExceptionThrowingVerification(),
        ]);

        // dla celów testowych tworzymy katalog gdzie zapisywane będą eventy
        system('mkdir -p /tmp/customer-events');
    }

    protected function tearDown(): void
    {
        // czyścimy po testach
        system('rm -rf /tmp/customer-events/*');
    }

    /**
     * Zakładamy, z punktu widzenia, biznesowego, że potrafimy obsłużyć sytuację rzucenia wyjątku.
     * W naszym przypadku jest to uzyskanie wyniku procesowania klienta nawet jeśli wyjątek został rzucony.
     * Nie chcemy sytuacji, w której rzucony błąd wpłynie na nasz proces biznesowy.
     */
    public function test_should_handle_exceptions_gracefully_when_dealing_with_results(): void
    {
        $this->markTestSkipped('Ten test nie przejdzie');

        self::assertEquals(
            ['age', 'id', 'name', 'exception'],
            array_map(
                fn (VerificationResult $result) => $result->name(),
                $this->verifier->verify(new Customer(Uuid::v4(), $this->tooYoungStefan()))
            )
        );
    }

    /**
     * Poprawiamy problem z kodu wyżej. Metoda produkcyjna `verifyNoException` potrafi obsłużyć rzucony wyjątek z osobnego wątku.
     */
    public function test_should_handle_exceptions_gracefully_when_dealing_with_results_passing(): void
    {
        self::assertEquals(
            ['age', 'id', 'name', 'exception'],
            array_map(
                fn (VerificationResult $result) => $result->name(),
                $this->verifier->verifyNoException(new Customer(Uuid::v4(), $this->tooYoungStefan()))
            )
        );
    }

    private function tooYoungStefan(): Person
    {
        return new Person('', '', (new \DateTimeImmutable())->modify('-1 year'), Person::GENDER_MALE, '0123456789');
    }
}
