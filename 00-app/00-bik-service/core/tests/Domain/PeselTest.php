<?php

declare(strict_types=1);

namespace SmartTesting\Bik\Score\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SmartTesting\Bik\Score\Domain\Pesel;

final class PeselTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateNewPesel(): void
    {
        $peselString = '91121345678';
        $pesel = new Pesel($peselString);

        self::assertSame($peselString, $pesel->pesel());
    }

    /**
     * @test
     *
     * @dataProvider peselDataProvider
     */
    public function shouldThrowExceptionIfPeselLengthNotEqualToEleven(string $pesel): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Pesel($pesel);
    }

    public function peselDataProvider(): \Generator
    {
        yield from [['9112134567'], ['911213456789']];
    }
}
