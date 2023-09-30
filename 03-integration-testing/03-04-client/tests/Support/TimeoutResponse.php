<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Support;

use donatj\MockWebServer\RequestInfo;
use donatj\MockWebServer\ResponseInterface;

final class TimeoutResponse implements ResponseInterface
{
    public function getRef(): string
    {
        return 'timeout';
    }

    public function getBody(RequestInfo $request): string
    {
        // tutaj celowo wprowadzamy nieskończoną pętlę, aby zasymulować niekończące się połączenie
        while (true) {
            sleep(1);
        }
    }

    public function getHeaders(RequestInfo $request): array
    {
        return [];
    }

    public function getStatus(RequestInfo $request): int
    {
        return 200;
    }
}
