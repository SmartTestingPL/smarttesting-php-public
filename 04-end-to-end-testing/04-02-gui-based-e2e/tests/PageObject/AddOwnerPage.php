<?php

declare(strict_types=1);

namespace SmartTesting\Tests\PageObject;

use Facebook\WebDriver\WebDriverBy;

class AddOwnerPage extends PageObject
{
    public function fillFirstName(string $firstName): void
    {
        $this->driver->findElement(WebDriverBy::id('firstName'))->sendKeys($firstName);
    }

    public function fillLastName(string $lastName): void
    {
        $this->driver->findElement(WebDriverBy::id('lastName'))->sendKeys($lastName);
    }

    public function fillAddress(string $address): void
    {
        $this->driver->findElement(WebDriverBy::id('address'))->sendKeys($address);
    }

    public function fillCity(string $city): void
    {
        $this->driver->findElement(WebDriverBy::id('city'))->sendKeys($city);
    }

    public function fillTelephoneNumber(string $telephoneNumber): void
    {
        $this->driver->findElement(WebDriverBy::id('telephone'))->sendKeys($telephoneNumber);
    }

    public function addOwner(): OwnerViewPage
    {
        $this->driver->findElement(WebDriverBy::xpath('/html/body/div/div/form/div[2]/div/button'))->click();

        return new OwnerViewPage($this->driver);
    }
}
