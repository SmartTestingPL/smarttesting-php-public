<?php

declare(strict_types=1);

namespace SmartTesting;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use SmartTesting\Tests\PageObject\HomePage;

/**
 * Przykład zastosowania wzorca PageObjectModel.
 */
class PetClinicPageObjectModelTest extends TestCase
{
    private RemoteWebDriver $driver;

    protected function setUp(): void
    {
        $desiredCapabilities = DesiredCapabilities::firefox();
        $desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);
        $this->driver = RemoteWebDriver::create('http://localhost:4444', $desiredCapabilities);
        $this->driver->get('http://localhost:8080/');
    }

    protected function tearDown(): void
    {
        $this->driver->quit();
    }

    public function testShouldAddOwner(): void
    {
        $faker = Factory::create();

        $findOwnersPage = (new HomePage($this->driver))->navigateToFindOwners();
        $addOwnerPage = $findOwnersPage->navigateToAddOwner();
        $addOwnerPage->fillFirstName($firstName = $faker->firstName);
        $addOwnerPage->fillLastName($faker->lastName);
        $addOwnerPage->fillAddress($faker->address);
        $addOwnerPage->fillCity($faker->city);
        $addOwnerPage->fillTelephoneNumber($faker->numerify('#########'));

        $ownerViewPage = $addOwnerPage->addOwner();

        self::assertTrue($ownerViewPage->containsText($firstName));
    }
}
