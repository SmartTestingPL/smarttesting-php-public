<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Model;

use Symfony\Component\Uid\Uuid;

interface VerificationRepository
{
    public function findById(Uuid $id): ?VerifiedPerson;

    public function save(VerifiedPerson $loanOrder): VerifiedPerson;
}
