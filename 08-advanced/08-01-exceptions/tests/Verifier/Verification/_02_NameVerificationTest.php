<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Verification;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Verification\_01_NameVerification;
use SmartTesting\Verifier\Verification\_04_VerificationException;
use SmartTesting\Verifier\Verification\_05_NameWithCustomExceptionVerification;

class _02_NameVerificationTest extends TestCase
{
    /**
     * Test, w którym weryfikujemy czy został rzucony bardzo generyczny wyjątek
     * InvalidArgumentException.
     *
     * Test ten przechodzi nam przypadkowo, gdyż IAE leci w innym miejscu w kodzie
     * niż się spodziewaliśmy.
     *
     * Uruchamiając ten test nie widzimy żeby zalogowała nam się linijka z kasy
     * {@link _01_NameVerification}...
     */
    public function test_should_throw_an_exception_when_checking_verification(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new _01_NameVerification())->passes($this->anna());
    }

    /**
     * Poprawiona wersja poprzedniego testu, gdzie tym razem zweryfikujemy
     * zawartość wiadomości w rzuconym wyjątku.
     *
     * Zakomentuj `markTestSkipped`, żeby zobaczyć, że test się wysypuje, gdyż
     * nie jest wołana nasza wersja InvalidArgumentException, tylko domyślna,
     * z konstruktora Person
     *
     * Problem polega na tym, że w konstruktorze Person weryfikowana jest płeć, a w testach
     * ustawiliśmy złą (z dużej liter zamiast małej, ogólnie można użyć consta i nie byłoby problemu)
     */
    public function test_should_throw_an_exception_when_checking_verification_only(): void
    {
        $this->markTestSkipped();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Name cannot be empty');

        (new _01_NameVerification())->passes($this->anna());
    }

    /**
     * W momencie, w którym nasza aplikacja rzuca wyjątki domenowe, wtedy nasz test
     * może po prostu spróbować go wyłapać.
     *
     * Zakomentuj `markTestSkipped`, żeby zobaczyć, że test się wysypuje, gdyż wyjątek,
     * który poleci to InvalidArgumentException, a nie _04_VerificationException.
     */
    public function test_should_fail_verification_when_name_is_invalid(): void
    {
        $this->markTestSkipped();

        $this->expectException(_04_VerificationException::class);

        (new _05_NameWithCustomExceptionVerification())->passes($this->anna());
    }

    /**
     * Koncepcyjnie to samo co powyżej. Do zastosowania w momencie, w którym
     * nie posiadacie dedykowanych bibliotek do asercji, takich jak np. Phpunit.
     *
     * Łapiemy w {@code try {...} catch {...} } wywołanie metody, która powinna rzucić wyjątek.
     * Koniecznie należy wywalić test, jeśli wyjątek nie zostanie rzucony!!!
     *
     * W sekcji {@code catch} możemy wykonać dodatkowe asercje na rzuconym wyjątku.
     */
    public function test_should_fail_verification_when_name_is_invalid_and_assertion_is_done_manually(): void
    {
        $this->markTestSkipped();

        try {
            (new _05_NameWithCustomExceptionVerification())->passes($this->anna());
            // Koniecznie należy wywalić test, jeśli wyjątek nie zostanie rzucony!!!
            $this->fail('This should not happen');
        } catch (_04_VerificationException $exception) {
            // Dodatkowe asercje błędu jeśli potrzebne
            self::assertEquals('Name cannot be empty', $exception->getMessage());
        }
    }

    private function anna(): Person
    {
        return new Person('Anna', 'Smith', new \DateTimeImmutable(), 'FEMALE', '0000000000');
    }
}
