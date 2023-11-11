<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class _04_FraudVerifierFailingTest extends WebTestCase
{
    public function test_should_return_fraud_when_client_has_debt(): void
    {
        $this->markTestSkipped();

        $verifier = new _04_FraudVerifier();

        $result = $verifier->verify($this->clientWithDebt());

        self::assertEquals(_04_VerificationResult::FRAUD(), $result);
    }

    public function test_should_return_not_fraud_when_client_has_no_debt(): void
    {
        $this->markTestSkipped();

        $verifier = new _04_FraudVerifier();

        $result = $verifier->verify($this->clientWithoutDebt());

        self::assertEquals(_04_VerificationResult::NOT_FRAUD(), $result);
    }

    private function clientWithDebt(): _04_Client
    {
        return new _03_Client(true);
    }

    private function clientWithoutDebt(): _04_Client
    {
        return new _03_Client(false);
    }
}

class _04_FraudVerifier
{
    public function verify(_03_Client $client): _04_VerificationResult
    {
    }
}

class _04_Client implements \JsonSerializable
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

class _04_VerificationResult
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
}
