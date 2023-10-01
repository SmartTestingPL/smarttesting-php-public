<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * Encja bazodanowa. Wykorzystujemy ORM (mapowanie obiektowo relacyjne) i obiekt
 * tej klasy mapuje się na tabelę {@code verified}. Każde pole klasy to osobna kolumna
 * w bazie danych.
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="verified")
 */
class VerifiedPerson
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    private Uuid $userId;

    /**
     * @ORM\Column(type="string")
     */
    private string $nationalIdentificationNumber;

    /**
     * @ORM\Column(type="string")
     */
    private string $status;

    public function __construct(Uuid $userId, string $nationalIdentificationNumber, string $status)
    {
        $this->userId = $userId;
        $this->nationalIdentificationNumber = $nationalIdentificationNumber;
        $this->status = $status;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function userId(): Uuid
    {
        return $this->userId;
    }

    public function nationalIdentificationNumber(): string
    {
        return $this->nationalIdentificationNumber;
    }

    public function status(): string
    {
        return $this->status;
    }
}
