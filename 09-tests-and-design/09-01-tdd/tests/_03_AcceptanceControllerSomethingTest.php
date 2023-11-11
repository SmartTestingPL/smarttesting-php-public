<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class _03_AcceptanceControllerSomethingTest extends WebTestCase
{
    public function test_should_verify_a_client_with_debt_as_fraud(): void
    {
        $this->markTestSkipped();

        $fraud = $this->clientWithDebt();

        $verification = $this->verifyClient($fraud);

        $this->thenIsVerifiedAsFraud($verification);
    }

    private function clientWithDebt(): _03_Client
    {
        return new _03_Client(true);
    }

    private function verifyClient(_03_Client $client): array
    {
        return $this->sendAnHttpJsonToFraudCheck($client);
    }

    private function thenIsVerifiedAsFraud(array $verification): void
    {
        self::assertEquals(['status' => 'fraud'], $verification);
    }

    private function sendAnHttpJsonToFraudCheck(_03_Client $client): array
    {
        $client = static::createClient();
        $client->request('POST', '/fraudcheck', [], [], [], json_encode($client, JSON_THROW_ON_ERROR));

        return json_decode((string) $client->getResponse()->getContent(), true, JSON_THROW_ON_ERROR);
    }
}

class _03_FraudController extends AbstractController
{
    private _03_Something $something;

    public function __construct(_03_Something $something)
    {
        $this->something = $something;
    }

    /**
     * @Route("/fraudcheck", methods={"POST"})
     */
    public function fraud(Request $request): Response
    {
        $client = _03_Client::fromRequest($request);

        return new Response($this->something->something($client));
    }
}

class _03_Something
{
    public function something(_03_Client $client): _03_VerificationResult
    {
    }
}

class _03_Client implements \JsonSerializable
{
    private bool $hasDept;

    public function __construct(bool $hasDept)
    {
        $this->hasDept = $hasDept;
    }

    public static function fromRequest(Request $request): self
    {
        $data = json_decode($request->getContent(), true, JSON_THROW_ON_ERROR);

        return new self($data['hasDept']);
    }

    public function jsonSerialize(): array
    {
        return ['hasDept' => $this->hasDept];
    }
}

class _03_VerificationResult implements \JsonSerializable
{
    private const FRAUD = 'fraud';
    private const NOT_FRAUD = 'not-fraud';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function FRAUD(): self
    {
        return new self(self::FRAUD);
    }

    public static function NOT_FRAUD(): self
    {
        return new self(self::NOT_FRAUD);
    }

    public function jsonSerialize(): array
    {
        return ['status' => $this->value];
    }
}
