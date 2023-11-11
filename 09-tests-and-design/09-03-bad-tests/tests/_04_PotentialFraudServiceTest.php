<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Klasa testowa pokazująca jak stanowość może pokrzyżować nam plany w powtarzalnych
 * wynikach testów.
 *
 * Najpierw zakomentuj {@link markTestSkipped}, żeby wszystkie testy się uruchomiły.
 *
 * Następnie uruchom testy kilkukrotnie - zobaczysz, że czasami przechodzą, a czasami nie.
 * W czym problem?
 */
class _04_PotentialFraudServiceTest extends TestCase
{
    /**
     * Test ten oczekuje, że zawsze uruchomi się pierwszy. Dlatego oczekuje, że w cacheu
     * będzie jeden wynik. Dla przypomnienia, cache jest współdzielony przez wszystkie
     * testy, ponieważ jest statyczny.
     *
     * W momencie uruchomienia testów w innej kolejności, inne testy też dodają wpisy
     * do cachea. Zatem nie ma możliwości, żeby rozmiar cachea wynosił 1.
     */
    public function test_should_count_potential_frauds(): void
    {
        $this->markTestSkipped();

        $cache = new PotentialFraudCache();
        $service = new PotentialFraudService($cache);
        $service->setFraud('Kowalski');

        self::assertEquals(1, $cache->size());
    }

    /**
     * Przykład testu, który weryfikuje czy udało nam się dodać wpis do cachea.
     * Zwiększa rozmiar cachea o 1. Gdy ten test zostanie uruchomiony przed
     * {@link test_should_count_potential_frauds} - wspomniany test się wywali.
     */
    public function test_should_set_potential_fraud(): void
    {
        $cache = new PotentialFraudCache();
        $service = new PotentialFraudService($cache);
        $service->setFraud('Oszustowski');

        self::assertNotNull($cache->get('Oszustowski'));
    }

    /**
     * Potencjalne rozwiązanie problemu wspóldzielonego stanu. Najpierw
     * zapisujemy stan wejściowy - jaki był rozmiar cachea. Dodajemy wpis
     * do cachea i sprawdzamy czy udało się go dodać i czy rozmiar jest większy niż był.
     *
     * W przypadku uruchomienia wielu testów równolegle, sam fakt weryfikacji rozmiaru
     * jest niedostateczny, gdyż inny test mógł zwiększyć rozmiar cachea. Koniecznym
     * jest zweryfikowanie, że istnieje w cacheu wpis dot. Kradzieja.
     *
     * BONUS: Jeśli inny test weryfikował usunięcie wpisu z cachea, to asercja
     * na rozmiar może nam się wysypać. Należy rozważyć, czy nie jest wystarczającym
     * zweryfikowanie tylko obecności Kradzieja w cacheu!
     */
    public function test_should_store_potential_fraud(): void
    {
        $cache = new PotentialFraudCache();
        $service = new PotentialFraudService($cache);
        $initialSize = $cache->size();

        $service->setFraud('Kradziej');

        self::assertGreaterThan($initialSize, $cache->size());
        self::assertNotNull($cache->get('Kradziej'));
    }
}

class PotentialFraudCache
{
    /**
     * Stan współdzielony między instancjami.
     *
     * @var array<string, PotentialFraud>
     */
    private static array $cache = [];

    public function size(): int
    {
        return count(self::$cache);
    }

    public function get(string $name): ?PotentialFraud
    {
        if (!isset(self::$cache[$name])) {
            return null;
        }

        return self::$cache[$name];
    }

    public function put(PotentialFraud $fraud): void
    {
        self::$cache[$fraud->name()] = $fraud;
    }
}

/**
 * Serwis aplikacyjny opakowujący wywołania do cachea.
 */
class PotentialFraudService
{
    private PotentialFraudCache $cache;

    public function __construct(PotentialFraudCache $cache)
    {
        $this->cache = $cache;
    }

    public function setFraud(string $name): void
    {
        $this->cache->put(new PotentialFraud($name));
    }
}

/**
 * Struktura reprezentująca potencjalnego oszusta.
 */
class PotentialFraud
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}
