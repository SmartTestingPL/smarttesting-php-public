<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Cost;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use SmartTesting\Bik\Score\Cost\SymfonyRestClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class SymfonyRestClientTest extends KernelTestCase
{
    private static MockWebServer $server;
    private static int $port;

    public static function setUpBeforeClass(): void
    {
        self::$port = self::findFreePort();
        self::$server = new MockWebServer(self::$port, 'localhost');
    }

    protected function setUp(): void
    {
        self::$server->start();
        parent::bootKernel();
    }

    public function tearDown(): void
    {
        self::$server->stop();
    }

    /**
     * @test
     */
    public function should_return_correct_response(): void
    {
        self::$server->setResponseOfPath('/test', new Response('response'));
        $client = self::$container->get(SymfonyRestClient::class);
        $response = $client->get('http://localhost:'.self::$port.'/test');

        self::assertEquals('response', $response);
    }

    private static function findFreePort()
    {
        $sock = socket_create_listen(0);
        socket_getsockname($sock, $addr, $port);
        socket_close($sock);

        return $port;
    }
}
