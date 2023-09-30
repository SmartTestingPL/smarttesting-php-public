<?php

declare(strict_types=1);

namespace SmartTesting\Client;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Uproszczony klient do komunikacji z Biurem Informacji Kredytowej z użyciem klienta HTTP.
 *
 * W tej implementacji chcemy pokazać jakie problemy można zaobserwować w momencie,
 * w którym nie weryfikujemy jakie wartości domyślne są używane przez nasze narzędzia.
 */
class BIKVerificationService
{
    private string $bikServiceUri;
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;

    public function __construct(string $bikServiceUri, HttpClientInterface $client = null, LoggerInterface $logger = null)
    {
        $this->bikServiceUri = $bikServiceUri;
        $this->httpClient = $client ?? HttpClient::create();
        $this->logger = $logger ?? new NullLogger();
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        try {
            $response = $this->httpClient->request('GET', $this->bikServiceUri.$customer->person()->nationalIdentificationNumber());
            if ($response->getStatusCode() === 200 && $response->getContent() === 'VERIFICATION_PASSED') {
                return CustomerVerificationResult::passed($customer->uuid());
            }
        } catch (ExceptionInterface $exception) {
            $this->processException($exception);
        }

        return CustomerVerificationResult::failed($customer->uuid());
    }

    protected function processException(ExceptionInterface $exception): void
    {
        $this->logger->error('Http request execution failed', ['exception' => $exception]);
    }
}
