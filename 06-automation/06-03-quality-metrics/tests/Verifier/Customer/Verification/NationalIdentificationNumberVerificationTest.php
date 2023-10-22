<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer\Verification;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\Verification\IdentificationNumberVerification;

/**
 * Klasa zawiera przykłady różnych konwencji nazewniczych metod testowych. Warto jest trzymać
 * się jednej dla całej organizacji.
 */
class NationalIdentificationNumberVerificationTest extends TestCase
{
    public function testVerificationShouldPassForCorrectIdentificationNumber(): void
    {
        // given
        $person = $this->buildPerson('1998-03-14', Person::GENDER_FEMALE);
        $verification = new IdentificationNumberVerification();

        // when
        $passes = $verification->passes($person);

        // then
        self::assertTrue($passes);
    }

    public function testVerificationShouldFailForInconsistentGender(): void
    {
        // given
        $person = $this->buildPerson('1998-03-14', Person::GENDER_MALE);
        $verification = new IdentificationNumberVerification();

        // when
        $passes = $verification->passes($person);

        // then
        self::assertFalse($passes);
    }

    /**
     * @test
     */
    public function shouldReturnFalseForWrongYearOfBirth(): void
    {
        // given
        $person = $this->buildPerson('2000-03-14', Person::GENDER_FEMALE);
        $verification = new IdentificationNumberVerification();

        // when
        $passes = $verification->passes($person);

        // then
        self::assertFalse($passes);
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
