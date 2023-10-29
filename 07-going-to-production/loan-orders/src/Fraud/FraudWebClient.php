<?php

declare(strict_types=1);

namespace SmartTesting\Fraud;

use SmartTesting\Customer\Customer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Zipkin\Instrumentation\Http\Client\HttpClientTracing;
use ZipkinBundle\Components\HttpClient\HttpClient as ZipkinHttpClient;

class FraudWebClient
{
    private string $baseUrl;
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient, HttpClientTracing $tracing)
    {
        $this->httpClient = new ZipkinHttpClient($httpClient, $tracing);
        $this->baseUrl = 'http://127.0.0.1:8001';
    }

    public function verifyCustomer(Customer $customer): CustomerVerificationResult
    {
        $response = $this->httpClient->request('POST', $this->baseUrl.'/customers', [
            'json' => $customer,
        ]);

        $response = json_decode($response->getContent(), true);

        if ($response['status'] === 'passed') {
            return CustomerVerificationResult::passed($customer->uuid());
        }

        return CustomerVerificationResult::failed($customer->uuid());
    }
}
