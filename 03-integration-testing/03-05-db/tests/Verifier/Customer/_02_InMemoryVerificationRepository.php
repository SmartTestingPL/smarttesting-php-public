<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use SmartTesting\Verifier\Customer\VerificationRepository;
use SmartTesting\Verifier\Customer\VerifiedPerson;
use Symfony\Component\Uid\Uuid;

class _02_InMemoryVerificationRepository implements VerificationRepository
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
