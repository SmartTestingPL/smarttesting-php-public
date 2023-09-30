<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use PHPUnit\Framework\TestCase;
use SmartTesting\Client\Person;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Uid\Uuid;

/**
 * Ten test tworzy najpierw ręcznie kontener zależności a następnie wyciąga z niego
 * gotowy serwis CustomerVerifier wykorzystując schemat z pliku services.yml.
 *
 * Oddzielamy w ten sposób konstrukcję obiektu
 * $customerVerifier = $this->container->get('customerVerifier');
 *
 * od użycia
 * $result = $customerVerifier->verify($this->stefan());
 */
class _02_SymfonyDiTest extends TestCase
{
    private static ContainerInterface $container;

    /**
     * zbudowanie kontenera z reguły jest ciężką operacją więc powinno wykonać się ją raz przed wszystkim testami.
     */
    public static function setUpBeforeClass(): void
    {
        // stworzenie kontenera
        self::$container = new ContainerBuilder();
        // załadowanie konfiguracji z pliku yml (można również wykorzystać xml oraz php)
        $loader = new YamlFileLoader(self::$container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');
        // kompilacja kontenera (zbudowanie drzewa zależności oraz cache)
        self::$container->compile();
    }

    public function testSymfonyContainer(): void
    {
        // wyciągniecie obiektu z kontenera
        $customerVerifier = self::$container->get('customerVerifier');

        // wywołanie logiki
        $result = $customerVerifier->verify($this->stefan());

        // asercja
        self::assertFalse($result->isPassed());
    }

    private function stefan(): Person
    {
        return new Person(Uuid::v4(), '', '', new \DateTimeImmutable(), Person::GENDER_MALE, '');
    }
}
