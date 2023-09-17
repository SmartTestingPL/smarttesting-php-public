<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

/**
 * Klasa "wrapper" otaczająca statyczną metodę, która realizuje jakieś ciężkie operacje bazodanowe.
 * Nie polecamy robienia czegoś takiego w metodzie statycznej, ale tu pokazujemy jak to obejść i przetestować
 * jeżeli z jakiegoś powodu nie da się tego zmienić (np. metoda statyczna jest dostarczana przez kogoś innego).
 */
class VeryBadVerificationServiceWrapper
{
    public function verify(): bool
    {
        return VeryBadVerificationService::runHeavyQueriesToDatabaseFromStaticMethod();
    }
}
