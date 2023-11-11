<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer\Verification;

use Assert\Assert;
use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\Verification\AgeVerification;
use SmartTesting\Verifier\EventEmitter;

/**
 * Klasa zawiera przykłady asercji z wykorzystaniem bibliotek do asercji.
 * używanie innych bibliotek do asercji może wymagać dodania dodatkowej flagi do konfiguracji:.
 *
 * beStrictAboutTestsThatDoNotTestAnything = false
 */
class AgeVerificationTest extends TestCase
{
    private AgeVerification $verification;

    protected function setUp(): void
    {
        $this->verification = new AgeVerification(new EventEmitter());
    }

    public function testVerificationShouldPassForAgeBetween18And99(): void
    {
        // given
        $person = $this->buildPerson(22);

        // when
        $passes = $this->verification->passes($person);

        // then
        Assert::that($passes)->true();
    }

    public function testShouldReturnFalseWhenUserOlderThan99(): void
    {
        // given
        $person = $this->buildPerson(100);

        // when
        $passes = $this->verification->passes($person);

        // then
        Assert::that($passes)->false();
    }

    public function testInvalidArgumentExceptionThrownWhenAgeBelowZero(): void
    {
        // given
        $person = $this->buildPerson(-1);

        // then
        $this->expectException(\InvalidArgumentException::class);

        // when
        $this->verification->passes($person);
    }

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
