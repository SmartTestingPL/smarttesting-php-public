<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

/**
 * Komponent odpowiedzialny za wysyłanie wiadomości z oszustem.
 */
interface FraudAlertNotifier
{
    public function fraudFound(CustomerVerification $verification): void;
}
