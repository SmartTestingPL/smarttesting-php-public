<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Cost;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SymfonyRestClient implements RestClient
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get(string $url): string
    {
        return $this->httpClient->request(Request::METHOD_GET, $url)->getContent();
    }
}
