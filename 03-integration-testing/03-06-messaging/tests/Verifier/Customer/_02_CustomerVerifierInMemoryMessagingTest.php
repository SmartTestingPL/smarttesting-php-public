<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerification;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\FraudAlertNotifier;
use SmartTesting\Verifier\Customer\VerificationRepository;
use Symfony\Component\Uid\Uuid;

/**
 * W tej klasie testowej piszemy test dla serwisu CustomerVerifier, który
 * zamiast produkcyjnej instancji komponentu wysyłającego wiadomości,
 * użyje komponentu zawierającego kolejkę w pamięci.
 */
class _02_CustomerVerifierInMemoryMessagingTest extends TestCase
{
    private VerificationRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(VerificationRepository::class);
    }

    public function test_should_send_a_message_with_fraud_details_when_found_a_fraud(): void
    {
        $messaging = new InMemoryMessaging();
        $fraud = $this->fraud();

        $this->alwaysFailingCustomerVerifier($messaging)->verify(new Customer(Uuid::v4(), $fraud));

        $verification = $messaging->poll();
        self::assertNotNull($verification);
        self::assertEquals($fraud->nationalIdentificationNumber(), $verification->person()->nationalIdentificationNumber());
    }

    private function fraudCustomerVerification(Person $person, Uuid $userId): CustomerVerification
    {
        return new CustomerVerification($person, CustomerVerificationResult::failed($userId));
    }

    private function alwaysFailingCustomerVerifier(FraudAlertNotifier $notifier): CustomerVerifier
    {
        return new CustomerVerifier([new AlwaysFailingVerification()], $this->repository, $notifier);
    }

    private function fraud(): Person
    {
        return new Person('Fraud', 'Fraudowski', new \DateTimeImmutable(), Person::GENDER_MALE, '1234567890');
    }
}
