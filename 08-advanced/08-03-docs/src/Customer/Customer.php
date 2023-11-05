<?php

declare(strict_types=1);

namespace SmartTesting\Customer;

use OpenApi\Annotations as OA;
use Symfony\Component\Uid\Uuid;

/**
 * W celu poprawnego generowania specyfikacji openapi wszystkie widoczne pola modelu
 * muszą mieć gettery zaczynające sie od "get".
 */
class Customer
{
    private Uuid $uuid;
    private Person $person;

    public function __construct(Uuid $uuid, Person $person)
    {
        $this->uuid = $uuid;
        $this->person = $person;
    }

    /**
     * @OA\Property(type="string")
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
