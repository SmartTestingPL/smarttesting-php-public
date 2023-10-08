<?php

declare(strict_types=1);

namespace SmartTesting;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

/**
 * Przykład testu z wykorzystaniem nakładki na selenium: symfony/panther
 * nie wymaga uruchomienie drivera, jest on wykrywany i uruchamiany automatycznie.
 */
class PetClinicPantherTest extends PantherTestCase
{
    public static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createPantherClient([
            'external_base_uri' => 'http://localhost:8080',
            'browser' => self::FIREFOX,
        ]);
    }

    public function testShouldDisplayErrorMessage(): void
    {
        self::$client->get('/');
        self::$client->clickLink('Error');

        self::assertStringContainsString('Something happened...', self::$client->getPageSource());
    }

    public function testAddOwner(): void
    {
        self::$client->get('/owners/new');
        self::$client->waitFor('#add-owner-form');

        $form = self::$client->getCrawler()->filter('#add-owner-form')->form([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'address' => '1st Street 12',
            'city' => 'New York',
            'telephone' => '123456789',
        ]);
        self::$client->submit($form);

        self::assertStringContainsString('Owner Information', self::$client->getPageSource());
    }
}
