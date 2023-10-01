<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use PHPUnit\Framework\TestCase;
use SmartTesting\Client\CustomerVerifier;
use SmartTesting\Client\DatabaseAccessor;
use SmartTesting\Client\EventEmitter;
use SmartTesting\Client\HttpCallMaker;
use SmartTesting\Client\Person;
use SmartTesting\Client\Verification\AgeVerification;
use SmartTesting\Client\Verification\IdentificationNumberVerification;
use SmartTesting\Client\Verification\NameVerification;
use Symfony\Component\Uid\Uuid;

class _01_IocTest extends TestCase
{
    /**
     * Kod przedstawiony na slajdzie [W jaki sposób tworzysz obiekty?].
     * Przedstawiamy tu ręczne utworzenie drzewa zależności obiektów.
     * Trzeba pamiętać o odpowiedniej kolejności utworzenia obiektów oraz
     * w jednym miejscu mieszamy tworzenie i realizacje akcji biznesowej
     * wywołanie:.
     *
     * (new CustomerVerifier(...))->verify(...);
     */
    public function testManualObjectGeneration(): void
    {
        // Tworzenie Age Verification
        $httpCallMaker = new HttpCallMaker();
        $accessor = new DatabaseAccessor();
        $ageVerification = new AgeVerification($httpCallMaker, $accessor);

        // Tworzenie ID Verification
        $idVerification = new IdentificationNumberVerification($accessor);

        // Tworze Name Verification
        $eventEmitter = new EventEmitter();
        $nameVerification = new NameVerification($eventEmitter);

        // Wywołanie logiki
        $result = (new CustomerVerifier([$ageVerification, $idVerification, $nameVerification]))
            ->verify($this->stefan());

        // Asercja
        self::assertFalse($result->isPassed());
    }

    private function stefan(): Person
    {
        return new Person(Uuid::v4(), '', '', new \DateTimeImmutable(), Person::GENDER_MALE, '');
    }
}
