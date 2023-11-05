<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer\Verification;

use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification;

/**
 * Weryfikacja poprawności numeru PESEL.
 * Zob.: https://pl.wikipedia.org/wiki/PESEL#Cyfra_kontrolna_i_sprawdzanie_poprawno.C5.9Bci_numeru.
 */
class IdentificationNumberVerification implements Verification
{
    public function passes(Person $person): bool
    {
        $passes = $this->genderMatchesIdentificationNumber($person)
            && $this->identificationNumberStartsWithDateOfBirth($person)
            && $this->identificationNumberWeightIsCorrect($person);

        return $passes;
    }

    private function genderMatchesIdentificationNumber(Person $person): bool
    {
        $secondLast = (int) substr($person->getNationalIdentificationNumber(), 9, 1);
        if ($secondLast % 2 === 0) {
            return $person->getGender() === Person::GENDER_FEMALE;
        }

        return $person->getGender() === Person::GENDER_MALE;
    }

    private function identificationNumberStartsWithDateOfBirth(Person $person): bool
    {
        $dateOfBirth = $person->getDateOfBirth()->format('ymd');
        if ($dateOfBirth[0] === '0') {
            $monthNum = (int) substr($dateOfBirth, 2, 2);
            $monthNum += 20;
            $dateOfBirth = substr($dateOfBirth, 0, 2).$monthNum.substr($dateOfBirth, 4, 2);
        }

        return $dateOfBirth === substr($person->getNationalIdentificationNumber(), 0, 6);
    }

    private function identificationNumberWeightIsCorrect(Person $person): bool
    {
        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        if (strlen($person->getNationalIdentificationNumber()) !== 11) {
            return false;
        }

        $weightSum = 0;
        for ($i = 0; $i < 10; ++$i) {
            $weightSum += ((int) $person->getNationalIdentificationNumber()[$i]) * $weights[$i];
        }
        $actualSum = (10 - $weightSum % 10) % 10;
        $checkSum = (int) substr($person->getNationalIdentificationNumber(), 10, 1);

        return $actualSum === $checkSum;
    }
}
