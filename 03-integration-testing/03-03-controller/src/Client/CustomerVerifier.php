<?php

declare(strict_types=1);

namespace SmartTesting\Client;

/**
 * Weryfikacja czy klient jest oszustem czy nie. Przechodzi po
 * różnych implementacjach weryfikacji i jeśli, przy którejś okaże się,
 * że użytkownik jest oszustem, wówczas odpowiedni rezultat zostanie zwrócony.
 */
class CustomerVerifier
{
    /**
     * @var Verification[]
     */
    private array $verifications = [];

    public function __construct(iterable $verifications)
    {
        foreach ($verifications as $verification) {
            $this->verifications[] = $verification;
        }
    }

    public function verify(Person $person): CustomerVerificationResult
    {
        $allMatch = true;
        foreach ($this->verifications as $verification) {
            if (!$verification->passes($person)) {
                $allMatch = false;
                break;
            }
        }

        if ($allMatch) {
            return CustomerVerificationResult::passed($person->uuid());
        }

        return CustomerVerificationResult::failed($person->uuid());
    }
}
