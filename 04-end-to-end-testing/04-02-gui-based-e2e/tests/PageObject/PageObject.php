<?php

declare(strict_types=1);

namespace SmartTesting\Tests\PageObject;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverWait;

abstract class PageObject
{
    protected RemoteWebDriver $driver;

    public function __construct(RemoteWebDriver $driver)
    {
        $this->driver = $driver;
    }

    public function containsText(string $text): bool
    {
        return strstr($this->driver->getPageSource(), $text) !== false;
    }

    protected function pageReady(): void
    {
        (new WebDriverWait($this->driver, 20))->until(function (RemoteWebDriver $driver) {
            return $this->driver->executeScript('return document.readyState;') === 'complete';
        });
    }
}
