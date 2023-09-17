<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Personal;

use SmartTesting\Bik\Score\Domain\Score;
use SmartTesting\Bik\Score\Personal\Occupation;
use SmartTesting\Bik\Score\Personal\OccupationRepository;

// Test double dla OccupationRepository; dodaliśmy tylko jeden element do mapy, bo na potrzeby tych testów,
// interesują nas tak na prawdę tutaj tylko 2 sytuacje: 1) dany zawód jest w repozytorium, 2) danego zawodu nie ma w repozytorium
final class TestOccupationRepository implements OccupationRepository
{
    /**
     * @return array<string, Score>
     */
    public function getOccupationScores(): array
    {
        return [
            Occupation::programmer()->toString() => new Score(30),
        ];
    }
}
