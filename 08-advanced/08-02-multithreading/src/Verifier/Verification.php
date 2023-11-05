<?php

declare(strict_types=1);

namespace SmartTesting\Verifier;

use SmartTesting\Customer\Person;

/**
 * Weryfikacja klienta.
 */
interface Verification
{
    /**
     * Weryfikuje czy dana osoba nie jest oszustem.
     *
     * @param Person $person - osoba do zweryfikowania
     *
     * @return bool - false dla oszusta
     */
    public function passes(Person $person): bool;

    public function name(): string;
}
