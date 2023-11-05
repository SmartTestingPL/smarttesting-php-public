<?php

declare(strict_types=1);

namespace SmartTesting\Customer;

/**
 * Reprezentuje osobę do zweryfikowania.
 */
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

    public function age(): int
    {
        $diff = $this->dateOfBirth->diff(new \DateTimeImmutable());

        return $diff->y * ($diff->invert ? -1 : 1);
    }
}
