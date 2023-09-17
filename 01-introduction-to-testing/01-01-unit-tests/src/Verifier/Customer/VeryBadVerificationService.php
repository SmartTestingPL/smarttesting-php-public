<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

class VeryBadVerificationService
{
    public static function runHeavyQueriesToDatabaseFromStaticMethod(): bool
    {
        // Metoda odpalająca ciężkie zapytania do bazy danych i ściągająca pół internetu.
        sleep(10);

        return true;
    }
}
