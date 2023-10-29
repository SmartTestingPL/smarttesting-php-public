<?php

declare(strict_types=1);

namespace SmartTesting\Customer;

use Symfony\Component\Uid\Uuid;

/**
 * Klient. Klasa opakowująca osobę do zweryfikowania.
 */
class Customer implements \JsonSerializable
{
    private Uuid $uuid;
    private Person $person;

    public function __construct(Uuid $uuid, Person $person)
    {
        $this->uuid = $uuid;
        $this->person = $person;
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function person(): Person
    {
        return $this->person;
    }

    public function isStudent(): bool
    {
        return $this->person->isStudent();
    }

    public function student(): void
    {
        $this->person->student();
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'person' => $this->person,
        ];
    }
}
