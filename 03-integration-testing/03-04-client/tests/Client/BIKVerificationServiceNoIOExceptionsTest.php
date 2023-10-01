<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use PHPUnit\Framework\TestCase;
use SmartTesting\Client\BIKVerificationService;
use SmartTesting\Client\Customer;
use SmartTesting\Client\Person;
use SmartTesting\Tests\Support\TimeoutResponse;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Uid\Uuid;

/**
 * Klasa testowa wykorzystująca ręcznie ustawione wartości połączenia po HTTP.
 * W tym przypadku, domyślna implementacja BIKVerificationService, w przypadku błędu
 * zaloguje informacje o wyjątku.
 *
 * W tej klasie testowej pokazujemy jak powinniśmy przetestować naszego klienta HTTP.
 * Czy potrafimy obsłużyć wyjątki? Czy potrafimy obsłużyć scenariusze biznesowe?
 *
 * O problemach związanych z pisaniem zaślepek przez konsumenta API, będziemy mówić
 * w dalszej części szkolenia. Tu pokażemy ręczne zaślepianie scenariuszy
 * biznesowych.
 */
class BIKVerificationServiceNoIOExceptionsTest extends TestCase
{
    private static MockWebServer $server;
    private static int $port;
    private BIKVerificationService $service;

    /**
     * Tworzymy instancję naszego mock serwera (dostępna będzie dla wszystkich testów w tej klasie)
     * celowo wybieramy losowy port.
     */
    public static function setUpBeforeClass(): void
    {
        self::$port = self::findFreePort();
        self::$server = new MockWebServer(self::$port, 'localhost');
    }

    /**
     * Przed i każdym teście czyszczone i ustawiane są odpowiednie wartości domyślnie
     * (specyfika tej biblioteki, pozwala to potem na nagrywanie żądań i ich weryfikację).
     *
     * Tym razem podmieniamy również domyślną konfigurację klienta HTTP dla BIKVerificationService
     */
    public function setUp(): void
    {
        self::$server->start();
        $this->service = new BIKVerificationService(
            'http://localhost:'.self::$port.'/',
            HttpClient::create(['timeout' => 1]) // ustawiamy domyślny timeout na 1 sekundę
        );
    }

    public function tearDown(): void
    {
        self::$server->stop();
    }

    public function testShouldReturnPositiveVerification(): void
    {
        self::$server->setResponseOfPath('/18210116954', new Response('VERIFICATION_PASSED'));

        self::assertTrue($this->service
            ->verify($this->youngZbigniew())
            ->isPassed()
        );
    }

    public function testShouldReturnNegativeVerification(): void
    {
        self::$server->setResponseOfPath('/18210116954', new Response('VERIFICATION_FAILED'));

        self::assertFalse($this->service
            ->verify($this->youngZbigniew())
            ->isPassed()
        );
    }

    /**
     * W tym i kolejnych testach zaślepiamy wywołanie GET zwracając różne
     * błędy techniczne. Chcemy się upewnić, że potrafimy je obsłużyć.
     */
    public function testShouldFailWithIdleConnection(): void
    {
        self::$server->setResponseOfPath('/18210116954', new TimeoutResponse());

        self::assertFalse($this->service
            ->verify($this->youngZbigniew())
            ->isPassed()
        );
    }

    public function testShouldFailWithEmptyResponse(): void
    {
        self::$server->setResponseOfPath('/18210116954', new Response('VERIFICATION_FAILED'));

        self::assertFalse($this->service
            ->verify($this->youngZbigniew())
            ->isPassed()
        );
    }

    public function testShouldFailOnTransportException(): void
    {
        self::$server->stop();

        self::assertFalse($this->service
            ->verify($this->youngZbigniew())
            ->isPassed()
        );
    }

    private function youngZbigniew(): Customer
    {
        return new Customer(Uuid::v4(), new Person('', '', new \DateTimeImmutable(), Person::GENDER_MALE, '18210116954'));
    }

    private static function findFreePort()
    {
        $sock = socket_create_listen(0);
        socket_getsockname($sock, $addr, $port);
        socket_close($sock);

        return $port;
    }
}
