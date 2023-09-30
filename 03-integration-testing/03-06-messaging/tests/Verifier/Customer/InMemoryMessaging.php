<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Verifier\Customer\CustomerVerification;
use SmartTesting\Verifier\Customer\FraudAlertNotifier;

class InMemoryMessaging implements FraudAlertNotifier
{
    /**
     * @var CustomerVerification[]
     */
    private array $queue = [];

    public function fraudFound(CustomerVerification $verification): void
    {
        $this->queue[] = $verification;
    }

    public function poll(): ?CustomerVerification
    {
        return array_shift($this->queue);
    }
}
