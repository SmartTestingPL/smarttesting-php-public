<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use donatj\MockWebServer\MockWebServer;
use PHPUnit\Framework\TestCase;
use SmartTesting\Client\BIKVerificationService;
use SmartTesting\Client\Customer;
use SmartTesting\Client\Person;
use SmartTesting\Tests\Support\TimeoutResponse;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

/**
 * Klasa testowa wykorzystująca ręcznie ustawione wartości połączenia po HTTP.
 * W tym przypadku, nasza implementacja BIKVerificationService ma nadpisaną metodę
 * pakietową, która zamiast logować wyjątek będzie go rzucać.
 *
 * W tej klasie testowej pokazujemy jak wysypała by się nasza aplikacja, gdybyśmy
 * odpowiednio nie obsłużyli w niej wyjątków.
 */
class BIKVerificationServiceTest extends TestCase
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
     * Tym razem podmieniamy również domyślną konfigurację klienta HTTP dla BIKVerificationService.
     *
     * Nadpisujemy też metodę, obsługującą rzucony wyjątek.
     * W tym przypadku będziemy go ponownie rzucać.
     */
    public function setUp(): void
    {
        self::$server->start();
        $this->service = new class('http://localhost:'.self::$port.'/', HttpClient::create(['timeout' => 1])) extends BIKVerificationService {
            protected function processException(ExceptionInterface $exception): void
            {
                throw $exception;
            }
        };
    }

    public function tearDown(): void
    {
        self::$server->stop();
    }

    public function testShouldFailWithTimeoutException(): void
    {
        self::$server->setResponseOfPath('/18210116954', new TimeoutResponse());

        self::expectException(TimeoutException::class);
        $this->service->verify($this->youngZbigniew());
    }

    public function testShouldFailWithTransportException(): void
    {
        // tutaj celowo zatrzymuje serwer co spowoduje brak możliwości połączenie się na wybranym porcie
        self::$server->stop();

        self::expectException(TransportException::class);

        $this->service->verify($this->youngZbigniew());
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
