<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;

class _01_NoAssertionsTest extends TestCase
{
    /**
     * Test bez asercji. PHPUnit oznaczy je jako risky.
     */
    public function test_should_return_sum_when_adding_two_numbers(): void
    {
        $first = 1;
        $second = 2;

        $this->thenTwoNumbersShouldBeAdded($first + $second);
    }

    /**
     * Poprawiony test składający się z samej asercji.
     */
    public function test_should_return_sum_when_adding_two_numbers_correct(): void
    {
        self::assertEquals(3, 1 + 2);
    }

    private function thenTwoNumbersShouldBeAdded(int $result): void
    {
        // brakuje asercji!
    }
}
