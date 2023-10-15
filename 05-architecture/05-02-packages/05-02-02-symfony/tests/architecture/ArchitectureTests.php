<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class ArchitectureTests extends ArchitectureTest
{
    /**
     * Test weryfikujący, że żadne klasy z namespace {@code SmartTesting\Verifier\Model} nie zależą
     * od klas z namespace {@code SmartTesting\Verifier\Infrastructure}.
     */
    public function testShouldNotContainAnyInfrastructureInModelDomain(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('SmartTesting\Verifier\Model\*'))
            ->mustNotDependOn()
            ->classesThat(Selector::haveClassName('SmartTesting\Verifier\Infrastructure\*'))
            ->build();
    }
}
