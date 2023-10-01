<?php

declare(strict_types=1);

namespace SmartTesting\Client;

use Symfony\Component\Uid\Uuid;

/**
 * Reprezentuje osobę do zweryfikowania.
 */
class Person
{
    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';

    private Uuid $uuid;
    private string $name;
    private string $surname;
    private \DateTimeImmutable $dateOfBirth;
    private string $gender;
    private string $nationalIdentificationNumber;

    public function __construct(Uuid $uuid, string $name, string $surname, \DateTimeImmutable $dateOfBirth, string $gender, string $nationalIdentificationNumber)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->surname = $surname;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->nationalIdentificationNumber = $nationalIdentificationNumber;
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function surname(): string
    {
        return $this->surname;
    }

    public function dateOfBirth(): \DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function gender(): string
    {
        return $this->gender;
    }

    public function nationalIdentificationNumber(): string
    {
        return $this->nationalIdentificationNumber;
    }

    public function age(): int
    {
        return $this->dateOfBirth->diff(new \DateTimeImmutable())->y;
    }
}
