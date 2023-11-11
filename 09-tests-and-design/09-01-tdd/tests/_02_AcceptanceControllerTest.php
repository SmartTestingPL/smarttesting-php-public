<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class _02_AcceptanceControllerTest extends WebTestCase
{
    public function test_should_verify_a_client_with_debt_as_fraud(): void
    {
        $this->markTestSkipped();

        $fraud = $this->clientWithDebt();

        $verification = $this->verifyClient($fraud);

        $this->thenIsVerifiedAsFraud($verification);
    }

    private function clientWithDebt(): _02_Client
    {
        return new _02_Client(true);
    }

    private function verifyClient(_02_Client $client): array
    {
        return $this->sendAnHttpJsonToFraudCheck($client);
    }

    private function thenIsVerifiedAsFraud(array $verification): void
    {
        self::assertEquals(['status' => 'fraud'], $verification);
    }

    private function sendAnHttpJsonToFraudCheck(_02_Client $client): array
    {
        $client = static::createClient();
        $client->request('POST', '/fraudcheck', [], [], [], json_encode($client, JSON_THROW_ON_ERROR));

        return json_decode((string) $client->getResponse()->getContent(), true, JSON_THROW_ON_ERROR);
    }
}

class _02_FraudController extends AbstractController
{
    /**
     * @Route("/fraudcheck", methods={"POST"})
     */
    public function fraud(Request $request): Response
    {
        return new Response(null, 204);
    }
}

class _02_Client implements \JsonSerializable
{
    private bool $hasDept;

    public function __construct(bool $hasDept)
    {
        $this->hasDept = $hasDept;
    }

    public function jsonSerialize(): array
    {
        return ['hasDept' => $this->hasDept];
    }
}
