<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

class _03_HttpFraudControllerWithFakeTest extends TestCase
{
    protected function setUp(): void
    {
        (new Process(['symfony', 'server:start', '--port=8000', '--daemon'], null, ['APP_ENV' => 'test']))->mustRun();
    }

    public function testShouldAcceptLoanApplicationWhenPersonIsVeryOld(): void
    {
        $response = HttpClient::create()
            ->request('POST', 'https://localhost:8000/fraudCheck', ['json' => $this->veryOldZbigniew()]);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    private function veryOldZbigniew(): array
    {
        return [
            'uuid' => '7b3e02b3-6b1a-4e75-bdad-cef5b279b074',
            'name' => 'Zbigniew',
            'surname' => 'Zamłodowski',
            'dateOfBirth' => '1860-01-01',
            'gender' => 'male',
            'nationalIdentificationNumber' => '18210116954',
        ];
    }

    protected function tearDown(): void
    {
        (new Process(['symfony', 'server:stop']))->run();
    }
}
