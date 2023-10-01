<?php

declare(strict_types=1);

namespace SmartTesting\Client;

interface Verification
{
    /**
     * Weryfikuje czy dana osoba nie jest oszustem.
     * zwraca false dla oszusta.
     */
    public function passes(): bool;
}
