<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Cost;

interface RestClient
{
    public function get(string $url): string;
}
