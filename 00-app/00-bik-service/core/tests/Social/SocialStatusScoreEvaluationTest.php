<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Social;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Social\ContractType;
use SmartTesting\Bik\Score\Social\MaritalStatus;
use SmartTesting\Bik\Score\Social\SocialStatus;
use SmartTesting\Bik\Score\Social\SocialStatusClient;
use SmartTesting\Bik\Score\Social\SocialStatusScoreEvaluation;
use SmartTesting\Bik\Score\Social\Validation\NumberOfHouseholdMembersValidationException;
use SmartTesting\Bik\Score\Tests\CsvFileIterator;

final class SocialStatusScoreEvaluationTest extends TestCase
{
    private SocialStatusClient|MockObject $client;
    private SocialStatusScoreEvaluation $evaluation;

    protected function setUp(): void
    {
        $this->client = $this->createMock(SocialStatusClient::class);
        $this->evaluation = new SocialStatusScoreEvaluation($this->client);
    }

    /**
     * @test
     */
    public function shouldReturnZeroWhenNullSocialStatus(): void
    {
        $this->markTestSkipped();

        $this->client->method('getSocialStatus')->willReturn(null);

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(0, $score->points());
    }

    /**
     * @test
     *
     * @dataProvider numbersOfDependantsProvider
     */
    public function shouldThrowBusinessExceptionWhenIncorrectNumbersOfMembersAndDependants(
        int $numberOfDependants,
        int $numberOfHouseholdMembers
    ): void {
        $this->markTestSkipped();

        $this->client->method('getSocialStatus')->willReturn(new SocialStatus(
            $numberOfDependants,
            $numberOfHouseholdMembers,
            MaritalStatus::single(),
            ContractType::employmentContract()
        ));

        $this->expectException(NumberOfHouseholdMembersValidationException::class);

        $this->evaluation->evaluate(new Pesel('12345678901'));
    }

    /**
     * @test
     *
     * @dataProvider householdMembersCsvProvider
     */
    public function shouldCalculateScoreDependingOnNumberOfHouseholdMembers(
        string $numberOfHouseholdMembers,
        string $points
    ): void {
        $this->markTestSkipped();

        $this->client->method('getSocialStatus')->willReturn(new SocialStatus(
            0,
            (int) $numberOfHouseholdMembers,
            MaritalStatus::single(),
            ContractType::employmentContract()
        ));

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame((int) $points, $score->points());
    }

    /**
     * @test
     *
     * @dataProvider dependantsCsvProvider
     */
    public function shouldCalculateScoreDependingOnNumberOfDependants(
        string $numberOfDependants,
        string $points
    ): void {
        $this->client->method('getSocialStatus')->willReturn(new SocialStatus(
            (int) $numberOfDependants,
            6,
            MaritalStatus::single(),
            ContractType::employmentContract()
        ));

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame((int) $points, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreWhenCustomerSingle(): void
    {
        $this->client->method('getSocialStatus')->willReturn(
            new SocialStatus(0, 6, MaritalStatus::single(), ContractType::employmentContract())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(90, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreWhenCustomerMarriedAndEmploymentContract(): void
    {
        $this->client->method('getSocialStatus')->willReturn(
            new SocialStatus(0, 6, MaritalStatus::married(), ContractType::employmentContract())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(80, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreWhenOwnBusiness(): void
    {
        $this->client->method('getSocialStatus')->willReturn(
            new SocialStatus(0, 6, MaritalStatus::married(), ContractType::ownBusinessActivity())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(70, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreWhenUnemployed(): void
    {
        $this->client->method('getSocialStatus')->willReturn(
            new SocialStatus(0, 6, MaritalStatus::married(), ContractType::unemployed())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(60, $score->points());
    }

    public function numbersOfDependantsProvider(): \Generator
    {
        yield from [
            [0, 0],
            [-1, 0],
            [0, -1],
            [2, 1],
            [1, 1],
        ];
    }

    public function householdMembersCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../Resources/household-members.csv', 1);
    }

    public function dependantsCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../Resources/dependants.csv', 1);
    }
}
