<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PhpAT\Rule\Rule;
use PhpAT\Selector\Selector;
use PhpAT\Test\ArchitectureTest;

class ArchitectureTests extends ArchitectureTest
{
    public function testShouldNotContainAnyInfrastructureInCostDomain(): Rule
    {
        return $this->newRule
            ->classesThat(Selector::haveClassName('SmartTesting\Bik\Score\Cost\*'))
            ->mustNotDependOn()
            ->classesThat(Selector::haveClassName('SmartTesting\Bik\Score\Infrastructure\*'))
            ->build();
    }
}
