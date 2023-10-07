<?php

declare(strict_types=1);

namespace SmartTesting\Tests\E2e\Customer;

use Faker\Factory;
use Faker\Provider\pl_PL\Person;

class CustomerBuilder
{
    private string $uuid = '5c856b11-cf0c-42b7-9947-c032d319cfff';
    private string $name = 'Anna';
    private string $surname = 'Kowalska';
    private string $birthDate = '12-09-1978';
    private string $gender = 'female';
    private string $nationalIdentificationNumber = '78091211463';

    public function withBirthDate(string $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function adultMale(): self
    {
        $faker = Factory::create();
        $faker->addProvider(Person::class);

        $this->name = $faker->firstNameMale;
        $this->surname = $faker->lastNameMale;
        $this->birthDate = $faker->date('d-m-Y', '-21 years');
        $this->nationalIdentificationNumber = $faker->pesel;
        $this->gender = 'male';

        return $this;
    }

    public function build(): array
    {
        return [
            'uuid' => $this->uuid,
            'person' => [
                'name' => $this->name,
                'surname' => $this->surname,
                'dateOfBirth' => $this->birthDate,
                'gender' => $this->gender,
                'nationalIdentificationNumber' => $this->nationalIdentificationNumber,
            ],
        ];
    }
}
