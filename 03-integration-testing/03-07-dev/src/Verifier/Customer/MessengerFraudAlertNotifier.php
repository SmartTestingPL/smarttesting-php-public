<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Implementacja notyfikowania o oszuście. Wysyła wiadomość do brokera.
 * W tym przypadku używamy symfony/messenger co pozwala przeźroczyście zarządzać nam konfiguracją brokera
 * Możemy wybrać jedną z kilku gotowych implementacji (lub stworzyć własną): AMQP, Doctrine, Redis, In Memory.
 */
class MessengerFraudAlertNotifier implements FraudAlertNotifier
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function fraudFound(CustomerVerification $verification): void
    {
        $this->messageBus->dispatch($verification);
    }
}
