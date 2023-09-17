<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Domain;

final class Pesel
{
    private string $pesel;

    public function __construct(string $pesel)
    {
        if (strlen($pesel) != 11) {
            throw new \InvalidArgumentException('PESEL must be of 11 chars');
        }
        $this->pesel = $pesel;
    }

    public function pesel(): string
    {
        return $this->pesel;
    }
}
