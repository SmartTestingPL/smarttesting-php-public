<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use App\Verifier\Client;
use App\Verifier\FraudVerifier;
use App\Verifier\VerificationResult;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class _05_FraudVerifierTest extends WebTestCase
{
    public function test_should_return_fraud_when_client_has_debt(): void
    {
        $verifier = new FraudVerifier();

        $result = $verifier->verify($this->clientWithDebt());

        self::assertEquals(VerificationResult::FRAUD(), $result);
    }

    public function test_should_return_not_fraud_when_client_has_no_debt(): void
    {
        $verifier = new FraudVerifier();

        $result = $verifier->verify($this->clientWithoutDebt());

        self::assertEquals(VerificationResult::NOT_FRAUD(), $result);
    }

    private function clientWithDebt(): Client
    {
        return new Client(true);
    }

    private function clientWithoutDebt(): Client
    {
        return new Client(false);
    }
}
