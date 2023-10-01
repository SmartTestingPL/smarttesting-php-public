<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * W tej klasie testowej piszemy test dla serwisu CustomerVerifier.
 * Przed uruchomieniem właściwych testów, dzięki użyciu docker-compose
 * odpalimy w kontenerze Dockerowym, brokera RabbitMQ.
 */
class _03_CustomerVerifierWithContainersTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::bootKernel();
    }

    public function test_should_send_a_message_to_a_broker_when_fraud_was_found(): void
    {
        $customer = $this->zbigniew();

        $this::$container->get('verifier')->verify($customer);

        $transport = $this::$container->get('messenger.transport.amqp');

        $messages = iterator_to_array($transport->get());

        self::assertCount(1, $messages);
        self::assertTrue($customer->uuid()->equals($messages[0]->getMessage()->result()->userId()));
        // w ten sposób wiadomość zniknie z kolejki i zostanie "skonsumowania"
        $transport->ack($messages[0]);
    }

    private function zbigniew(): Customer
    {
        return new Customer(Uuid::v4(), new Person('Zbigniew', 'Zbigniewowski', new \DateTimeImmutable(), Person::GENDER_MALE, '18210116954'));
    }
}
