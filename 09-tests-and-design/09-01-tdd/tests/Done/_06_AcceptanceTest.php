<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use App\Verifier\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class _06_AcceptanceTest extends WebTestCase
{
    public function test_should_verify_a_client_with_debt_as_fraud(): void
    {
        $fraud = $this->clientWithDebt();

        $verification = $this->verifyClient($fraud);

        $this->thenIsVerifiedAsFraud($verification);
    }

    private function clientWithDebt(): Client
    {
        return new Client(true);
    }

    private function verifyClient(Client $client): array
    {
        return $this->sendAnHttpJsonToFraudCheck($client);
    }

    private function thenIsVerifiedAsFraud(array $verification): void
    {
        self::assertEquals(['status' => 'fraud'], $verification);
    }

    private function sendAnHttpJsonToFraudCheck(Client $client): array
    {
        $appClient = static::createClient();
        $appClient->request('POST', '/fraudcheck', [], [], [], json_encode($client, JSON_THROW_ON_ERROR));

        return json_decode((string) $appClient->getResponse()->getContent(), true, JSON_THROW_ON_ERROR);
    }
}
