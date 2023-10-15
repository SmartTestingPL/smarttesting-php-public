<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Klasa z przykładami źle zaprojektowanego kodu.
 *
 * Testy nie zawierają asercji, są tylko po to by skopiować je na slajdy
 * z pewnością, że się nadal kompilują.
 *
 * Klasy implementacyjne poniżej nic nie robią. Chodzi jedynie o zaprezentowanie
 * pewnych koncepcji.
 */
class BadClassTest extends TestCase
{
    /**
     * Kod widoczny na slajdzie [Testy a architektura].
     * W komentarzach zakładamy pewne kwestie po spojrzeniu w
     * wyimaginowaną implementację (oczywiście na
     * potrzeby tej lekcji takiej implementacji nie ma - chodzi nam o pewne koncepcje).
     */
    public function test_should_build_an_object(): void
    {
        // imię musi być null, żeby uruchomić specjalny scenariusz uruchomienia
        // nie chcemy liczyć dodatkowych opłat ze względu na konto, zatem ustawiamy je na null
        // w kodzie okazuje się, że nikt nie korzysta z usług marketingu więc też ustawiamy na null
        $person = new Person(null, 'Kowalski', null, new PhoneService(), new TaxService(), null, null);

        self::assertInstanceOf(Person::class, $person);
    }

    /**
     * Czy zdarzyło Ci się, że dodawanie kolejnych testów było dla Ciebie drogą przez mękę?
     * Czy znasz przypadki, gdzie potrzebne były setki linijek kodu przygotowującego pod uruchomienie testu?
     * Oznacza to, że najprawdopodobniej albo nasz sposób testowania jest niepoprawny
     * albo architektura aplikacji jest zła.
     */
    public function test_should_use_a_lot_of_mocks(): void
    {
        $accountService = $this->createMock(AccountService::class);
        $accountService->method('calculate')->willReturn('PL123080123');
        $phoneService = $this->createMock(PhoneService::class);
        $phoneService->method('calculate')->willReturn('+4812371237');
        $taxService = $this->createMock(TaxService::class);
        $taxService->method('calculate')->willReturn('1_000_000PLN');
        $marketingService = $this->createMock(MarketingService::class);
        $marketingService->method('calculate')->willReturn('MAIL');
        $reportingService = $this->createMock(ReportingService::class);
        $reportingService->method('calculate')->willReturn('FAILED');

        $person = new Person('Jan', 'Kowlaski', $accountService, $phoneService, $taxService, $marketingService, $reportingService);

        // asercja bez sensu, żeby phpunit się nie czepiał ;)
        self::assertInstanceOf(Person::class, $person);
    }
}

class Person
{
    private ?string $name;
    private ?string $surname;
    private ?AccountService $accountService;
    private ?PhoneService $phoneService;
    private ?TaxService $taxService;
    private ?MarketingService $marketingService;
    private ?ReportingService $reportingService;

    public function __construct(?string $name, ?string $surname, ?AccountService $accountService, ?PhoneService $phoneService, ?TaxService $taxService, ?MarketingService $marketingService, ?ReportingService $reportingService)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->accountService = $accountService;
        $this->phoneService = $phoneService;
        $this->taxService = $taxService;
        $this->marketingService = $marketingService;
        $this->reportingService = $reportingService;
    }

    public function order(): string
    {
        return 'ordered';
    }

    public function count(): string
    {
        return 'calculated';
    }

    public function calculated(): string
    {
        return 'calculated';
    }
}

class AccountService
{
    public function calculate(): string
    {
        return '';
    }
}

class PhoneService
{
    public function calculate(): string
    {
        return '';
    }
}

class TaxService
{
    public function calculate(): string
    {
        return '';
    }
}

class MarketingService
{
    public function calculate(): string
    {
        return '';
    }
}

class ReportingService
{
    public function calculate(): string
    {
        return '';
    }
}
