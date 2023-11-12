<?php

declare(strict_types=1);

namespace SmartTesting\Tests\PassingMock;

use PHPUnit\Framework\TestCase;

class _15_FraudVerifierTest extends TestCase
{
    /**
     * Przykład testu, gdzie zakładamy, że nie musimy tworzyć wszystkich obiektów
     * i podmieniamy je mockiem. Jeśli zależność jest wymagana - test nam się wywali.
     */
    public function test_should_mark_client_with_debt_as_fraud(): void
    {
        $verifier = new _16_FraudVerifier(
            $this->createMock(PenaltyCalculatorImpl::class),
            $this->createMock(TaxHistoryRetrieverImpl::class),
            new DatabaseAccessorImpl()
        );

        self::assertTrue($verifier->isFraud('Fraudowski'));
    }

    /**
     * Przykład testu, gdzie zakładamy, że nie musimy tworzyć wszystkich obiektów
     * i podmieniamy je nullem. Niestety nie trafiamy.
     */
    public function test_should_calculate_penalty_when_fraud_applies_for_a_loan(): void
    {
        $this->markTestSkipped();

        $verifier = new _16_FraudVerifier(
            new PenaltyCalculatorImpl(),
            $this->createMock(TaxHistoryRetrieverImpl::class),
            $this->createMock(DatabaseAccessorImpl::class),
        );

        self::assertGreaterThan(0, $verifier->calculateFraudPenalty('Fraudowski'));
    }

    /**
     * Wygląda na to, że musimy przekazać jeszcze {@link TaxHistoryRetrieverImpl}.
     */
    public function test_should_calculate_penalty_when_fraud_applies_for_a_loan_with_both_deps(): void
    {
        $verifier = new _16_FraudVerifier(
            new PenaltyCalculatorImpl(),
            new TaxHistoryRetrieverImpl(),
            $this->createMock(DatabaseAccessorImpl::class),
        );

        self::assertGreaterThan(0, $verifier->calculateFraudPenalty('Fraudowski'));
    }
}

class _16_FraudVerifier
{
    private PenaltyCalculatorImpl $penalty;
    private TaxHistoryRetrieverImpl $history;
    private DatabaseAccessorImpl $accessor;

    public function __construct(PenaltyCalculatorImpl $penalty, TaxHistoryRetrieverImpl $history, DatabaseAccessorImpl $accessor)
    {
        $this->penalty = $penalty;
        $this->history = $history;
        $this->accessor = $accessor;
    }

    public function calculateFraudPenalty(string $name): int
    {
        // 5 000 linijek kodu dalej...

        // set client history to false, otherwise it won't work
        $lastRevenue = $this->history->returnLastRevenue(new Client($name, false));
        // set client history to true, otherwise it won't work
        $penalty = $this->penalty->calculatePenalty(new Client($name, true));

        return $lastRevenue / (50 + $penalty);
    }

    public function isFraud(string $name): bool
    {
        // 7 000 linijek kodu dalej ...

        return $this->accessor->getClientByName($name)->hasDebt();
    }
}

class PenaltyCalculatorImpl
{
    public function calculatePenalty(Client $client): int
    {
        return 100;
    }
}

class TaxHistoryRetrieverImpl
{
    public function returnLastRevenue(Client $client): int
    {
        return 150;
    }
}

class DatabaseAccessorImpl
{
    public function getClientByName(string $name): Client
    {
        return new Client('Fraudowski', true);
    }
}

class Client
{
    private string $name;
    private bool $hasDebt;

    public function __construct(string $name, bool $hasDebt)
    {
        $this->name = $name;
        $this->hasDebt = $hasDebt;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hasDebt(): bool
    {
        return $this->hasDebt;
    }
}
