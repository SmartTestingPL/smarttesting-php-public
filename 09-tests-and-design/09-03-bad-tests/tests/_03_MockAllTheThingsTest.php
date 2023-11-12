<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;

class _03_MockAllTheThingsTest extends TestCase
{
    /**
     * W tym teście mockujemy wszystko co się da.
     */
    public function test_should_find_any_empty_name(): void
    {
        $iterator = $this->createMock(\ArrayIterator::class);
        $iterator->method('valid')->willReturn(true, false);
        $iterator->method('current')->willReturn('');

        self::assertTrue((new _03_FraudService())->anyNameIsEmpty($iterator));
    }

    /**
     * Poprawiona wersja testu powyżej.
     * - Nie mockujemy listy - tworzymy ją.
     * - Nie mockujemy wywołań metody statycznej z biblioteki zewnętrznej.
     */
    public function test_should_find_any_empty_name_fixed(): void
    {
        $names = ['non empty', ''];

        self::assertTrue((new _03_FraudService())->anyNameIsEmpty($names));
    }

    /**
     * Przykład opakowania kodu, wywołującego metodę statyczną łączącą się do bazy danych.
     */
    public function test_should_do_some_work_in_database_when_empty_string_found(): void
    {
        $wrapper = $this->createMock(DatabaseAccessorWrapper::class);
        $wrapper->expects($this->once())->method('storeInDatabase');

        (new FraudServiceFixed($wrapper))->anyNameIsEmpty(['non empty', '']);
    }
}

/**
 * Klasa, w której wołamy metody statyczne.
 */
class _03_FraudService
{
    public function anyNameIsEmpty(iterable $names): bool
    {
        foreach ($names as $name) {
            if ($name === '') {
                DatabaseAccessor::storeInDatabase();

                return true;
            }
        }

        return false;
    }
}

/**
 * Poprawiona implementacja, gdzie zamiast wywołania statycznego {@link DatabaseAccessor}
 * wywołujemy naszą wersję {@link DatabaseAccessorWrapper}.
 */
class FraudServiceFixed
{
    private DatabaseAccessorWrapper $wrapper;

    public function __construct(DatabaseAccessorWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function anyNameIsEmpty(iterable $names): bool
    {
        foreach ($names as $name) {
            if ($name === '') {
                $this->wrapper->storeInDatabase();

                return true;
            }
        }

        return false;
    }
}

/**
 * Nasza klasa opakowująca wywołanie metody statycznej.
 */
class DatabaseAccessorWrapper
{
    public function storeInDatabase(): void
    {
        DatabaseAccessor::storeInDatabase();
    }
}

/**
 * Klasa symulująca dostęp do bazy danych.
 */
class DatabaseAccessor
{
    public static function storeInDatabase(): void
    {
    }
}
