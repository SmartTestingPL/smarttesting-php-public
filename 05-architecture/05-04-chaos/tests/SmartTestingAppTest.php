<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Process\Process;

/**
 * Klasa testowa wykorzystująca narzędzie pumba do zmiany zachowania kontenera dockerowego
 * w czasie rzeczywistym w celu weryfikacji hipotez stanu ustalonego w ramach eksperymentów
 * inżynierii chaosu.
 *
 * Możemy mieć te testy w ramach naszej suity testów, ale niekoniecznie zawsze będziemy chcieli automatycznie je uruchamiać.
 */
class SmartTestingAppTest extends TestCase
{
    private ?Process $pumba = null;

    /**
     * Hipoteza stanu ustalonego
     *     POST na URL “/fraudCheck”,  reprezentujący oszusta, odpowie statusem 401, w ciągu 500 ms
     * Metoda
     *     Włączamy opóźnienie mające miejsce w kontrolerze
     * Wycofanie
     *     Wyłączamy opóźnienie mające miejsce w kontrolerze.
     */
    public function test_should_return_401_within_500_ms_when_calling_fraud_check_with_introduced_latency(): void
    {
        // uruchomienie spowolnienia latency dla bazy danych
        $this->pumba = new Process(['scripts/pumba', 'netem', '--tc-image=gaiadocker/iproute2', '--duration=5m', 'delay', '--time=3000', '05-04-chaos_database_1']);
        $this->pumba->start();

        // start serwera
        (new Process(['symfony', 'server:start', '--port=8182', '--no-tls', '--daemon']))->mustRun();

        $response = HttpClient::create()->request('POST', 'http://127.0.0.1:8182/fraudCheck', [
            'json' => $this->fraud(),
            'timeout' => 0.5,
        ]);

        self::assertEquals(401, $response->getStatusCode());
    }

    /**
     * Hipoteza stanu ustalonego
     *     POST na URL “/fraudCheck”,  reprezentujący oszusta, odpowie statusem 401, w ciągu 500 ms
     * Metoda
     *     Włączamy błędy spowodowane integracją z bazą danych
     * Wycofanie
     *     Wyłączamy błędy spowodowane integracją z bazą danych.
     */
    public function test_should_return_401_within_500_ms_when_calling_fraud_check_with_database_issues(): void
    {
        // zabicie kontenera z bazą danych
        $this->pumba = new Process(['scripts/pumba', 'kill', '05-04-chaos_database_1']);
        $this->pumba->start();

        // start serwera
        (new Process(['symfony', 'server:start', '--port=8182', '--no-tls', '--daemon']))->mustRun();

        $response = HttpClient::create()->request('POST', 'http://127.0.0.1:8182/fraudCheck', [
            'json' => $this->fraud(),
            'timeout' => 0.5,
        ]);

        self::assertEquals(401, $response->getStatusCode());
    }

    /**
     * Po zakończeniu testów wyłączamy pumba, zatrzymujemy serwer i przywracamy ubity kontener.
     */
    protected function tearDown(): void
    {
        if ($this->pumba && $this->pumba->isRunning()) {
            $this->pumba->stop();
        }

        (new Process(['symfony', 'server:stop']))->run();

        // restart docker-compose po zabiciu kontenera poprzez użycie pumba
        (new Process(['docker-compose', 'restart']))->mustRun();
    }

    private function fraud(): array
    {
        return [
            'uuid' => '7b3e02b3-6b1a-4e75-bdad-cef5b279b074',
            'person' => [
                'name' => 'Zbigniew',
                'surname' => 'Zamłodowski',
                'dateOfBirth' => '01-01-2020',
                'gender' => 'male',
                'nationalIdentificationNumber' => '18210116954',
            ],
        ];
    }
}
