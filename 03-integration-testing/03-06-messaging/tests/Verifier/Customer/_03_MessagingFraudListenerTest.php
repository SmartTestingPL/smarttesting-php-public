<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use Psr\Log\LoggerInterface;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerification;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\EventListener\StopWorkerOnMessageLimitListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Worker;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * W tej klasie testowej piszemy test dla komponentu nasłuchującego wiadomości z brokera.
 * Przed uruchomieniem właściwych testów, dzięki użyciu docker-compose
 * odpalimy w kontenerze Dockerowym, brokera RabbitMQ.
 */
class _03_MessagingFraudListenerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::bootKernel();
    }

    public function test_should_send_a_message_to_a_broker_when_fraud_was_found(): void
    {
        $worker = $this->createWorker();

        $userId = Uuid::v4();
        $failedVerification = $this->failedVerification($userId);
        $this::$container->get('messenger.bus.default')->dispatch($failedVerification);

        $worker->run();

        $verified = $this::$container->get('verificationRepository')->findByUserId($userId);
        self::assertNotNull($verified);
        self::assertEquals('failed', $verified->status());
    }

    private function failedVerification($userId): CustomerVerification
    {
        return new CustomerVerification(
            new Person('Stefania', 'Stefanowska', new \DateTimeImmutable(), Person::GENDER_FEMALE, '18210145358'),
            CustomerVerificationResult::failed($userId)
        );
    }

    private function createWorker(int $limit = 1): Worker
    {
        $logger = $this::$container->get(LoggerInterface::class);
        $eventDispatcher = $this::$container->get(EventDispatcherInterface::class);
        $eventDispatcher->addSubscriber(new StopWorkerOnMessageLimitListener($limit, $logger));

        return new Worker(
            [$this::$container->get('messenger.transport.amqp')],
            $this::$container->get(MessageBusInterface::class),
            $eventDispatcher,
            $logger
        );
    }
}
