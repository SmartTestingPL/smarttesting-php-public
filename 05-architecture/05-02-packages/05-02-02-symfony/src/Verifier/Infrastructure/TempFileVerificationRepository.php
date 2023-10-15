<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Infrastructure;

use SmartTesting\Verifier\Model\VerificationRepository;
use SmartTesting\Verifier\Model\VerifiedPerson;
use Symfony\Component\Uid\Uuid;

class TempFileVerificationRepository implements VerificationRepository
{
    private string $dir;

    public function __construct()
    {
        $this->dir = sys_get_temp_dir().'/php-loan-orders';
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }

    public function findById(Uuid $id): ?VerifiedPerson
    {
        if (!file_exists($this->dir.'/'.(string) $id)) {
            return null;
        }

        return unserialize(file_get_contents($this->dir.'/'.(string) $id));
    }

    public function save(VerifiedPerson $person): VerifiedPerson
    {
        file_put_contents($this->dir.'/'.(string) $person->id(), serialize($person));

        return $person;
    }
}
