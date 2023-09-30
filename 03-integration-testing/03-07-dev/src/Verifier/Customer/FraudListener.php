<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

interface FraudListener
{
    public function onFraud(CustomerVerification $verification): void;
}
