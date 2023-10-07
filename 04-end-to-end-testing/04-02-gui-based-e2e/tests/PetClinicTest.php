<?php

declare(strict_types=1);

namespace SmartTesting;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Przykład źle napisanego testu z wykorzystaniem WebDrivera.
 * Można wykorzystać wbudowany geckodriver, uruchamiając przed testem polecenie:
 * drivers/geckodriver.
 */
class PetClinicTest extends TestCase
{
    private RemoteWebDriver $driver;

    protected function setUp(): void
    {
        $desiredCapabilities = DesiredCapabilities::firefox();
        $desiredCapabilities->setCapability('moz:firefoxOptions', ['args' => ['-headless']]);
        $this->driver = RemoteWebDriver::create('http://localhost:4444', $desiredCapabilities);
    }

    protected function tearDown(): void
    {
        $this->driver->quit();
    }

    /**
     * Test co prawda coś weryfikuje, ale jest bardzo nieczytelny i trudny do utrzymania.
     */
    public function testShouldAddOwner(): void
    {
        $faker = Factory::create();
        $this->driver->get('http://localhost:8080/');

        $findOwners = (new WebDriverWait($this->driver, 20))->until(function (RemoteWebDriver $driver) {
            return $driver->findElement(WebDriverBy::linkText('FIND OWNERS'));
        });
        $findOwners->click();

        $addOwnerButton = (new WebDriverWait($this->driver, 20))->until(function (RemoteWebDriver $driver) {
            return $driver->findElement(WebDriverBy::linkText('Add Owner'));
        });
        $addOwnerButton->click();

        $firstNameInput = (new WebDriverWait($this->driver, 20))->until(function (RemoteWebDriver $driver) {
            return $driver->findElement(WebDriverBy::id('firstName'));
        });
        $firstNameInput->sendKeys($firstName = $faker->firstName);
        $this->driver->findElement(WebDriverBy::id('lastName'))->sendKeys($faker->lastName);
        $this->driver->findElement(WebDriverBy::id('address'))->sendKeys($faker->address);
        $this->driver->findElement(WebDriverBy::id('city'))->sendKeys($faker->city);
        $this->driver->findElement(WebDriverBy::id('telephone'))->sendKeys($faker->numerify('#########'));

        $addOwnerSubmit = $this->driver->findElement(WebDriverBy::xpath('/html/body/div/div/form/div[2]/div/button'));
        $addOwnerSubmit->click();

        (new WebDriverWait($this->driver, 20))->until(function (RemoteWebDriver $driver) {
            return strstr($driver->getPageSource(), 'Owner Information') !== false;
        });

        self::assertStringContainsString($firstName, $this->driver->getPageSource());
    }
}
