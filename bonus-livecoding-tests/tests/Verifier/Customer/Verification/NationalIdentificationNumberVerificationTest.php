<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer\Verification;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Person;
use SmartTesting\Tests\CsvFileIterator;
use SmartTesting\Verifier\Customer\Verification\IdentificationNumberVerification;
use SmartTesting\Verifier\EventEmitter;

/**
 * Klasa zawiera przykłady testów parametryzowanych.
 */
class NationalIdentificationNumberVerificationTest extends TestCase
{
    private IdentificationNumberVerification $verification;

    protected function setUp(): void
    {
        $this->verification = new IdentificationNumberVerification(new EventEmitter());
    }

    public function testVerificationShouldPassForCorrectIdentificationNumber(): void
    {
        // given
        $person = $this->buildPerson('1998-03-14', Person::GENDER_FEMALE);

        // when
        $passes = $this->verification->passes($person);

        // then
        self::assertTrue($passes);
    }

    public function testVerificationShouldFailForInconsistentGender(): void
    {
        // given
        $person = $this->buildPerson('1998-03-14', Person::GENDER_MALE);

        // when
        $passes = $this->verification->passes($person);

        // then
        self::assertFalse($passes);
    }

    public function testShouldReturnFalseForWrongYearOfBirth(): void
    {
        // given
        $person = $this->buildPerson('2000-03-14', Person::GENDER_FEMALE);

        // when
        $passes = $this->verification->passes($person);

        // then
        self::assertFalse($passes);
    }

    public static function idVerificationArgumentsProvider(): \Generator
    {
        yield 'correct case' => ['1998-03-14', Person::GENDER_FEMALE, true];
        yield 'invalid gender' => ['1998-03-14', Person::GENDER_MALE, false];
        yield 'wrong year' => ['2000-03-14', Person::GENDER_FEMALE, false];
    }

    /**
     * Test tych samych przypadków co w 3 różnych testach powyżej przy pomocy
     * parametrów zwracanych przez metodę.
     *
     * @dataProvider idVerificationArgumentsProvider
     */
    public function testShouldVerifyNationalIdentificationNumberAgainstPersonalData(string $birthDate, string $gender, bool $expected): void
    {
        $person = $this->buildPerson($birthDate, $gender);

        $passes = $this->verification->passes($person);

        self::assertEquals($expected, $passes);
    }

    public static function idVerificationCsvProvider(): \Iterator
    {
        return new CsvFileIterator(__DIR__.'/../../../Resources/pesel.csv', 1);
    }

    /**
     * Test tych samych przypadków co w 3 różnych testach powyżej przy pomocy parametrów z pliku CSV.
     * Jest to przykład z specjalnie napisanym własnym iteratorem CsvFileIterator aby zaprezentować, że dane
     * nie zawsze muszą być w tablicach.
     *
     * @dataProvider idVerificationCsvProvider
     */
    public function testShouldVerifyNationalIdentificationNumberAgainstPersonalDataFromFile(string $birthDate, string $gender, string $expected): void
    {
        $person = $this->buildPerson($birthDate, $gender);

        $passes = $this->verification->passes($person);

        self::assertEquals($expected === 'true', $passes);
    }

    private function buildPerson(string $birthDate, string $gender): Person
    {
        return new Person(
            'Anna',
            'Doe',
            \DateTimeImmutable::createFromFormat('Y-m-d', $birthDate),
            $gender,
            '98031416402'
        );
    }
}
