<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\BIKVerificationService;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\SimpleVerification;
use SmartTesting\Verifier\Customer\Verification\AgeVerification;
use SmartTesting\Verifier\Customer\Verification\IdentificationNumberVerification;
use SmartTesting\Verifier\Customer\VeryBadVerificationServiceWrapper;
use Symfony\Component\Uid\Uuid;

/**
 * Klasa zawiera przykład false-positive, oraz przykład zastosowania Test Doubles.
 */
class CustomerVerifierTest extends TestCase
{
    public function testShouldVerifyCorrectPerson(): void
    {
        // given
        $customer = $this->buildCustomer();
        $service = new CustomerVerifier([
           new AgeVerification(),
           new IdentificationNumberVerification(),
        ], $this->verificationServiceWrapper(), $this->badServiceWrapper());

        // when
        $result = $service->verify($customer);

        // then
        self::assertTrue($result->isPassed());
        self::assertTrue($result->userId()->equals($customer->uuid()));
    }

    /**
     * Test, który przechodzi nawet bez implementacji.
     */
    public function testShouldFailSimpleVerification(): void
    {
        // given
        $customer = $this->buildCustomer();
        $service = new CustomerVerifier([
            new SimpleVerification(),
        ], $this->verificationServiceWrapper(), $this->badServiceWrapper());

        // when
        $result = $service->verify($customer);

        // then
        self::assertFalse($result->isPassed());
    }

    /**
     * Implementacja testowa (Test Double) w celu uniknięcia kontaktowania się z
     * zewnętrznym serwisem w testach jednostkowych.
     */
    private function verificationServiceWrapper(): BIKVerificationService
    {
        return new class() extends BIKVerificationService {
            public function __construct()
            {
                parent::__construct('http://example.com');
            }

            public function verify(Customer $customer): CustomerVerificationResult
            {
                return CustomerVerificationResult::passed($customer->uuid());
            }
        };
    }

    /**
     * Implementacja testowa (Test Double) w celu uniknięcia kontaktowania się z
     * zewnętrznym serwisem w testach jednostkowych.
     */
    private function badServiceWrapper(): VeryBadVerificationServiceWrapper
    {
        return new class() extends VeryBadVerificationServiceWrapper {
            public function verify(): bool
            {
                // do not run all these have database and network operations in a unit test
                return true;
            }
        };
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
