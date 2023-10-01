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
use SmartTesting\Verifier\Customer\MessengerFraudAlertNotifier;
use SmartTesting\Verifier\Customer\MessengerFraudListener;
use SmartTesting\Verifier\Customer\VerificationRepository;
use SmartTesting\Verifier\Customer\VerifiedPerson;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

/**
 * W tej klasie testowej
 * - piszemy test dla CustomerVerifiera i mockujemy komponent wysyłający wiadomości
 * - piszemy test dla komponentu wysyłającego wiadomość
 * - piszemy test dla nasłuchiwacza wiadomości.
 */
class _01_CustomerVerifierMockMessagingTest extends TestCase
{
    private VerificationRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(VerificationRepository::class);
    }

    /**
     * W tym teście testujemy serwis aplikacyjny, a mockujemy wysyłacza
     * wiadomości (FraudAlertNortifier). Nie testujemy żadnej integracji,
     * działamy na mockach. Testy są szybkie - mogłyby być tak napisane
     * dla corowej części naszej domeny.
     */
    public function test_should_send_a_message_with_fraud_details_when_found_a_fraud_using_mocks(): void
    {
        $messaging = $this->createMock(FraudAlertNotifier::class);
        $fraud = $this->fraud();
        $userId = Uuid::v4();

        $messaging->expects($this->once())
            ->method('fraudFound')
            ->with($this->fraudCustomerVerification($fraud, $userId));

        $this->alwaysFailingCustomerVerifier($messaging)->verify(new Customer($userId, $fraud));
    }

    /**
     * Przykład testu, w którym testujemy już sam komponent do wysyłki
     * wiadomości. Mockujemy message bus i weryfikujemy czy metoda na kliencie się wykonała.
     *
     * Nie sprawdzamy żadnej integracji, test niewiele nam daje.
     */
    public function test_should_send_a_message_using_symfony_messenger(): void
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $verification = $this->fraudCustomerVerification($this->fraud(), Uuid::v4());

        $messageBus->expects($this->once())->method('dispatch')->with($verification);

        (new MessengerFraudAlertNotifier($messageBus))->fraudFound($verification);
    }

    /**
     * W tym teście weryfikujemy czy nasłuchiwacz na wiadomości, w momencie uzyskania
     * wiadomości potrafi zapisać obiekt w bazie danych. Test ten nie integruje się
     * z brokerem wiadomości więc nie mamy pewności czy potrafimy zdeserializować
     * wiadomość. Z punktu widzenia nasłuchiwania zapis do bazy danych jest efektem
     * ubocznym więc, możemy rozważyć użycie mocka.
     */
    public function test_should_store_fraud_when_received_over_messaging(): void
    {
        $listener = new MessengerFraudListener($this->repository);
        $userId = Uuid::v4();
        $fraud = $this->fraud();

        $this->repository->expects($this->once())
            ->method('save')
            ->with(new VerifiedPerson($userId, $fraud->nationalIdentificationNumber(), 'failed'));

        $listener->onFraud($this->fraudCustomerVerification($fraud, $userId));
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
