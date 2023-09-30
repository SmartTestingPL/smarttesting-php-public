<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerification;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 *  W tej klasie testowej piszemy test dla komponentu wysyłającego wiadomość do brokera.
 * W teście `_03_CustomerVerifierWithContainersTest` już go przetestowaliśmy, natomiast
 * możemy przetestować go w izolacji. Wówczas w teście samego serwisu, możemy użyć mocka.
 *
 * W ten sposób uzyskujemy ładny test integracyjny z wydzielonym komponentem od wysyłki
 * wiadomości.
 */
class _04_MessagingFraudAlertNotifierWithContainersTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::bootKernel();
    }

    public function test_should_send_a_message_to_a_broker_when_fraud_was_found(): void
    {
        $userId = Uuid::v4();
        $this::$container->get('fraudNotifier')->fraudFound($this->failedVerification($userId));

        $transport = $this::$container->get('messenger.transport.amqp');
        $messages = iterator_to_array($transport->get());

        self::assertCount(1, $messages);
        self::assertTrue($userId->equals($messages[0]->getMessage()->result()->userId()));
        $transport->ack($messages[0]);
    }

    private function failedVerification($userId): CustomerVerification
    {
        return new CustomerVerification(
            new Person('Stefania', 'Stefanowska', new \DateTimeImmutable(), Person::GENDER_FEMALE, '18210145358'),
            CustomerVerificationResult::failed($userId)
        );
    }
}
