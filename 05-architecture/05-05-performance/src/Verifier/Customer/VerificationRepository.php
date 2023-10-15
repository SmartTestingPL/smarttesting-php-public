<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Symfony\Component\Uid\Uuid;

interface VerificationRepository
{
    public function findByUserId(Uuid $uuid): ?VerifiedPerson;

    public function save(VerifiedPerson $person): void;
}
