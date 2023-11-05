<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer\Verification;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\Verification\AgeVerification;

class AgeVerificationTest extends TestCase
{
    public function test_should_throw_exception_when_age_invalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new AgeVerification())->passes($this->zbigniewFromTheFuture());
    }

    public function test_should_return_positive_verification_when_age_is_within_the_threshold(): void
    {
        self::assertTrue((new AgeVerification())->passes($this->oldEnoughZbigniew()));
    }

    public function test_should_return_negative_verification_when_age_is_below_the_threshold(): void
    {
        self::assertFalse((new AgeVerification())->passes($this->tooYoungZbigniew()));
    }

    public function test_should_return_negative_verification_when_age_is_above_the_threshold(): void
    {
        self::assertFalse((new AgeVerification())->passes($this->tooOldZbigniew()));
    }

    //    odkomentuj aby zobaczyć jak testy mutacyjne przechodzą
    //
    //    public function test_should_return_negative_verification_when_age_is_in_lower_boundary(): void
    //    {
    //        self::assertTrue((new AgeVerification())->passes($this->lowerAgeBoundaryZbigniew()));
    //    }
    //
    //    public function test_should_return_negative_verification_when_age_is_in_upper_boundary(): void
    //    {
    //        self::assertTrue((new AgeVerification())->passes($this->upperAgeBoundaryZbigniew()));
    //    }

    private function zbigniewFromTheFuture()
    {
        return new Person(
            'Zbigniew',
            'Stefanowski',
            (new \DateTimeImmutable())->modify('+10 years'),
            Person::GENDER_MALE,
            '00000000000'
        );
    }

    private function oldEnoughZbigniew()
    {
        return new Person(
            'Zbigniew',
            'Stefanowski',
            (new \DateTimeImmutable())->modify('-25 years'),
            Person::GENDER_MALE,
            '00000000000'
        );
    }

    private function tooYoungZbigniew()
    {
        return new Person(
            'Zbigniew',
            'Stefanowski',
            (new \DateTimeImmutable())->modify('-1 day'),
            Person::GENDER_MALE,
            '00000000000'
        );
    }

    private function tooOldZbigniew()
    {
        return new Person(
            'Zbigniew',
            'Stefanowski',
            (new \DateTimeImmutable())->modify('-100 years'),
            Person::GENDER_MALE,
            '00000000000'
        );
    }

    private function lowerAgeBoundaryZbigniew()
    {
        return new Person(
            'Zbigniew',
            'Stefanowski',
            (new \DateTimeImmutable())->modify('-18 years'),
            Person::GENDER_MALE,
            '00000000000'
        );
    }

    private function upperAgeBoundaryZbigniew()
    {
        return new Person(
            'Zbigniew',
            'Stefanowski',
            (new \DateTimeImmutable())->modify('-99 years'),
            Person::GENDER_MALE,
            '00000000000'
        );
    }
}
