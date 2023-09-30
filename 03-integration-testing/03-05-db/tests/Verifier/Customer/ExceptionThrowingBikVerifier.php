<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Customer\BIKVerificationService;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;

/**
 * Testowa implementacja komunikacji z BIK. Rzuca wyjątkiem jeśli zostanie wywołana.
 * W ten sposób upewniamy się, że test się wysypie jeśli spróbujemy zawołać BIK.
 */
class ExceptionThrowingBikVerifier extends BIKVerificationService
{
    public function __construct()
    {
        parent::__construct('');
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        throw new \RuntimeException('Shouldn\'t call bik verification');
    }
}
