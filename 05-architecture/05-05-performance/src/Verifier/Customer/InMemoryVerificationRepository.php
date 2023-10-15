<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Symfony\Component\Uid\Uuid;

class InMemoryVerificationRepository implements VerificationRepository
{
    /**
     * @var VerifiedPerson[]
     */
    private array $persons = [];

    public function findByUserId(Uuid $uuid): ?VerifiedPerson
    {
        if (isset($this->persons[(string) $uuid])) {
            return $this->persons[(string) $uuid];
        }

        return null;
    }

    public function save(VerifiedPerson $person): void
    {
        $this->persons[(string) $person->userId()] = $person;
    }
}
