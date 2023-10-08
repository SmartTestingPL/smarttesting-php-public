<?php

declare(strict_types=1);

namespace SmartTesting\Tests\E2e;

use PHPUnit\Framework\TestCase;
use STS\Backoff\Backoff;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Przykład klasy basowej dla testów E2E po HTTP. Wszystkie szczegóły związane z implementacją
 * warstwy integracyjnej zostały wyniesione do metod pomocniczych przez co testy są o wiele bardziej czytelne.
 */
abstract class LoanOrdersHttpClientTestCase extends TestCase
{
    public const LOAN_ORDERS_URI = 'https://127.0.0.1:8002';

    private HttpClientInterface $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = HttpClient::create(['timeout' => 3]);
    }

    protected function createAndRetrieveLoanOrder(array $customer): array
    {
        $response = $this->httpClient->request('POST', self::LOAN_ORDERS_URI.'/orders', ['json' => $customer]);
        self::assertEquals(200, $response->getStatusCode());

        $orderId = $response->toArray(true)['id'];

        /**
         * Zastosowanie Backoff w celu uniknięcia false negatives
         * Staramy się pobrać order, maksymalnie 10 razy w ciągu 60 sekund,
         * warunkiem ponownej próby jest status inny niż 200 lub pojawienie się wyjątku.
         */
        $backoff = new Backoff(10, 'constant', 60, null, function ($attempt, $maxAttempts, ResponseInterface $result, $exception = null) {
            return $result->getStatusCode() !== 200 || $exception !== null;
        });

        $response = $backoff->run(function () use ($orderId) {
            return $this->httpClient->request('GET', self::LOAN_ORDERS_URI.'/orders/'.$orderId);
        });

        return $response->toArray(true);
    }
}
