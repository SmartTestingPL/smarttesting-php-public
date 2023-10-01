<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use Symfony\Component\Uid\Uuid;

/**
 * Klasa bazowa z przykładem buildera obiektu wykorzystywanego w teście.
 */
class CustomerTestBase extends TestCase
{
    public function builder(): CustomerBuilder
    {
        return new CustomerBuilder();
    }
}

class CustomerBuilder
{
    private Uuid $uuid;
    private string $name;
    private string $surname;
    private \DateTimeImmutable $dateOfBirth;
    private string $gender;
    private string $nationalIdentificationNumber;
    private bool $student;

    public function __construct()
    {
        $this->uuid = Uuid::v4();
        $this->name = 'Anna';
        $this->surname = 'Kowalska';
        $this->dateOfBirth = \DateTimeImmutable::createFromFormat('Y-m-d', '1978-09-12');
        $this->gender = Person::GENDER_FEMALE;
        $this->nationalIdentificationNumber = '78091211463';
        $this->student = false;
    }

    public function withUuid(Uuid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function witjSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function withDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function withGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function withNationalIdentificationNumber(string $nationalIdentificationNumber): self
    {
        $this->nationalIdentificationNumber = $nationalIdentificationNumber;

        return $this;
    }

    public function asStudent(): self
    {
        $this->student = true;

        return $this;
    }

    public function build(): Customer
    {
        $customer = new Customer($this->uuid, new Person(
            $this->name,
            $this->surname,
            $this->dateOfBirth,
            $this->gender,
            $this->nationalIdentificationNumber
        ));

        if ($this->student) {
            $customer->student();
        }

        return $customer;
    }
}
