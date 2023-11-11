<?php

declare(strict_types=1);

namespace App\Verifier;

class FraudVerifier
{
    public function verify(Client $client): VerificationResult
    {
        if ($client->hasDept()) {
            return VerificationResult::FRAUD();
        }

        return VerificationResult::NOT_FRAUD();
    }
}
