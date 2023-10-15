<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class ArchitectureTests extends ArchitectureTest
{
    /**
     * Test weryfikujący, że klasy z namespace SmartTesting nie zależą od obcych dependencji
     * z małym wyjątkiem (patrz komentarz poniżej).
     */
    public function testCoreDependencyShouldNotContainAnyExternalDependencies(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('SmartTesting\*'))
            ->canOnlyDependOn()
            ->classesThat(Selector::haveClassName('SmartTesting\*'))
            // pozwalamy tylko na komponent uuid z racji brak natywnej obsługi
            ->andClassesThat(Selector::haveClassName('Symfony\Component\Uid\Uuid'))
            ->build();
    }
}
