<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Customer\BIKVerificationService;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;

/**
 * Testowa implementacja komunikacji z BIK. Zwraca pozytywną weryfikację
 * (dana osoba nie jest oszustem).
 */
class AlwaysPassingBikVerifier extends BIKVerificationService
{
    public function __construct()
    {
        parent::__construct('');
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        return CustomerVerificationResult::passed($customer->uuid());
    }
}
