<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer\Verification;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\Verification\AgeVerification;

class AgeVerificationTest extends TestCase
{
    public function testVerificationShouldPassForAgeBetween18And99(): void
    {
        // given
        $person = $this->buildPerson(22);
        $verification = new AgeVerification();

        // when
        $passes = $verification->passes($person);

        // then
        self::assertTrue($passes);
    }

    public function testShouldReturnFalseWhenUserOlderThan99(): void
    {
        // given
        $person = $this->buildPerson(100);
        $verification = new AgeVerification();

        // when
        $passes = $verification->passes($person);

        // then
        self::assertFalse($passes);
    }

    /**
     * Weryfikacja wyjątku przy pomocy phpunit.
     */
    public function testInvalidArgumentExceptionThrownWhenAgeBelowZero(): void
    {
        // given
        $person = $this->buildPerson(-0);
        $verification = new AgeVerification();

        // then
        $this->expectException(\InvalidArgumentException::class);

        // when
        $verification->passes($person);
    }

    /**
     * Metoda pomocnicza tworząca obiekty wykorzystywane w testach używana w celu uzyskania
     * lepszej czytelności kodu i reużycia kodu.
     */
    private function buildPerson(int $age)
    {
        return new Person(
            'Anna',
            'Smith',
            (new \DateTimeImmutable())->modify('-'.$age.' years'),
            Person::GENDER_FEMALE,
            '00000000000'
        );
    }
}
