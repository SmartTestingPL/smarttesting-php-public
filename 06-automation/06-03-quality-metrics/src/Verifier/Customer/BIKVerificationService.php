<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SmartTesting\Customer\Customer;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Uproszczony klient do komunikacji z Biurem Informacji Kredytowej z użyciem klienta HTTP.
 * Klasa używana do pokazania jak na poziomie testów jednostkowych unikać komunikacji sieciowej.
 */
class BIKVerificationService
{
    private string $bikServiceUri;
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;

    public function __construct(string $bikServiceUri, LoggerInterface $logger = null)
    {
        $this->bikServiceUri = $bikServiceUri;
        $this->httpClient = HttpClient::create();
        $this->logger = $logger ?? new NullLogger();
    }

    public function verify(Customer $customer): CustomerVerificationResult
    {
        try {
            $response = $this->httpClient->request('GET', $this->bikServiceUri.$customer->person()->nationalIdentificationNumber());
            if ($response->getStatusCode() === 200) {
                return CustomerVerificationResult::passed($customer->uuid());
            }
        } catch (\Throwable $exception) {
            $this->logger->error('Http request execution failed', ['exception' => $exception]);
        }

        return CustomerVerificationResult::failed($customer->uuid());
    }
}
