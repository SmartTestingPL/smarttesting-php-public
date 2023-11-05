<?php

declare(strict_types=1);

namespace SmartTesting\Customer;

use OpenApi\Annotations as OA;

class Person
{
    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';

    private const STATUS_STUDENT = 'student';
    private const STATUS_NOT_STUDENT = 'not-student';

    private string $name;
    private string $surname;
    private \DateTimeImmutable $dateOfBirth;
    private string $gender;
    private string $nationalIdentificationNumber;
    private string $status = self::STATUS_NOT_STUDENT;

    public function __construct(string $name, string $surname, \DateTimeImmutable $dateOfBirth, string $gender, string $nationalIdentificationNumber)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->nationalIdentificationNumber = $nationalIdentificationNumber;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @OA\Property(type="string", format="d-m-Y")
     */
    public function getDateOfBirth(): \DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getNationalIdentificationNumber(): string
    {
        return $this->nationalIdentificationNumber;
    }

    public function isStudent(): bool
    {
        return $this->status === self::STATUS_STUDENT;
    }

    public function student(): void
    {
        $this->status = self::STATUS_STUDENT;
    }

    public function age(): int
    {
        return $this->dateOfBirth->diff(new \DateTimeImmutable())->y;
    }
}
