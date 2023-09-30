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

class _03_SymfonyTestDiTest extends TestCase
{
    private static ContainerInterface $container;

    /**
     * Za pomocą dodatkowej konfiguracji services_test.yaml używamy obecny produkcyjny schemat konstruowania obiektów
     * ale podmieniamy jedną zależność. Innymi słowy, podmieniamy na potrzeby testów jeden z obiektów
     * (łączący się z prawdziwą bazą danych) w drugi (który posiada bazę danych w pamięci).
     */
    public static function setUpBeforeClass(): void
    {
        self::$container = new ContainerBuilder();
        $loader = new YamlFileLoader(self::$container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');
        $loader->load('services_test.yaml');
        self::$container->compile();
    }

    public function testSymfonyContainer(): void
    {
        $customerVerifier = self::$container->get('customerVerifier');

        $result = $customerVerifier->verify($this->stefan());

        self::assertFalse($result->isPassed());
    }

    private function stefan(): Person
    {
        return new Person(Uuid::v4(), '', '', new \DateTimeImmutable(), Person::GENDER_MALE, '');
    }
}
