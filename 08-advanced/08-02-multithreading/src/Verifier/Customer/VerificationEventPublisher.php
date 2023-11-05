<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

class VerificationEventPublisher
{
    public function publish(string $verificationName): void
    {
        file_put_contents('/tmp/customer-events/'.$verificationName.'.txt', 'true');
    }

    /**
     * @return string[]
     */
    public function events(): array
    {
        return array_map(fn (string $filename) => str_replace('.txt', '', basename($filename)), glob('/tmp/customer-events/*.txt'));
    }
}
