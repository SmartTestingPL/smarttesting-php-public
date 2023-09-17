<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Personal;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Bik\Score\Domain\Pesel;
use SmartTesting\Bik\Score\Personal\Education;
use SmartTesting\Bik\Score\Personal\Occupation;
use SmartTesting\Bik\Score\Personal\PersonalInformation;
use SmartTesting\Bik\Score\Personal\PersonalInformationClient;
use SmartTesting\Bik\Score\Personal\PersonalInformationScoreEvaluation;
use SmartTesting\Bik\Score\Tests\CsvFileIterator;

final class PersonalInformationScoreEvaluationTest extends TestCase
{
    private PersonalInformationClient|MockObject $client;
    private PersonalInformationScoreEvaluation $evaluation;

    protected function setUp(): void
    {
        $this->client = $this->createMock(PersonalInformationClient::class);
        $this->evaluation = new PersonalInformationScoreEvaluation($this->client, new TestOccupationRepository());
    }

    /**
     * @test
     */
    public function shouldReturnZeroWhenNullPersonalInformation(): void
    {
        $this->markTestSkipped();

        $this->client->method('getPersonalInformation')->willReturn(null);

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(0, $score->points());
    }

    /**
     * @test
     *
     * @dataProvider workExperienceCsvProvider
     */
    public function shouldCalculateScoreBasedOnYearsOfExperience(string $yearsOfWorkExperience, string $points): void
    {
        $this->markTestSkipped();

        $this->client->method('getPersonalInformation')->willReturn(
            new PersonalInformation(Education::none(), (int) $yearsOfWorkExperience, Occupation::other())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame((int) $points, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreWhenForOccupationPresentInRepository(): void
    {
        $this->client->method('getPersonalInformation')->willReturn(
            new PersonalInformation(Education::none(), 0, Occupation::programmer())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(30, $score->points());
    }

    /**
     * @test
     */
    public function shouldUseZeroPointsDefaultWhenForOccupationNotInRepositoryAndNoEducation(): void
    {
        $this->client->method('getPersonalInformation')->willReturn(
            new PersonalInformation(Education::none(), 0, Occupation::doctor())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(0, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreForBasicEducation(): void
    {
        $this->client->method('getPersonalInformation')->willReturn(
            new PersonalInformation(Education::basic(), 0, Occupation::doctor())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(10, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreForMediumEducation(): void
    {
        $this->client->method('getPersonalInformation')->willReturn(
            new PersonalInformation(Education::medium(), 0, Occupation::doctor())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(30, $score->points());
    }

    /**
     * @test
     */
    public function shouldCalculateScoreForHighEducation(): void
    {
        $this->client->method('getPersonalInformation')->willReturn(
            new PersonalInformation(Education::high(), 0, Occupation::doctor())
        );

        $score = $this->evaluation->evaluate(new Pesel('12345678901'));

        self::assertSame(50, $score->points());
    }

    public function workExperienceCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../Resources/work-experience.csv', 1);
    }
}
