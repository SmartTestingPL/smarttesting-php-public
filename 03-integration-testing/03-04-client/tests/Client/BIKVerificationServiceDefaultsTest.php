<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use donatj\MockWebServer\MockWebServer;
use PHPUnit\Framework\TestCase;
use SmartTesting\Client\BIKVerificationService;
use SmartTesting\Client\Customer;
use SmartTesting\Client\Person;
use SmartTesting\Tests\Support\TimeoutResponse;
use Symfony\Component\Uid\Uuid;

/**
 * Klasa testowa wykorzystująca wartości domyślne w konfiguracji biblioteki
 * do tworzenia połączeń po HTTP.
 */
class BIKVerificationServiceDefaultsTest extends TestCase
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
     */
    public function setUp(): void
    {
        self::$server->start();
        $this->service = new BIKVerificationService('http://localhost:'.self::$port.'/');
    }

    public function tearDown(): void
    {
        self::$server->stop();
    }

    public function testShouldFailWithIdleConnection()
    {
        /*
         * zakomentowanie poniższej linijki spowoduje że ten test się nigdy nie zakończy
         * dzieje się ponieważ domyślny timeout klienta http ustawiony jest na null - co oznacza
         * nieskończone oczekiwanie na połączenie
         */
        $this->markTestSkipped('Ten test się nigdy nie zakończy');

        self::$server->setResponseOfPath('/18210116954', new TimeoutResponse());

        self::assertTrue($this->service
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
