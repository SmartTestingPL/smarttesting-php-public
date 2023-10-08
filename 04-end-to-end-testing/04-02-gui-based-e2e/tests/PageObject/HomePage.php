<?php

declare(strict_types=1);

namespace SmartTesting\Tests\PageObject;

use Facebook\WebDriver\WebDriverBy;

class HomePage extends PageObject
{
    public function navigateToFindOwners(): FindOwnersPage
    {
        $this->driver->findElement(WebDriverBy::linkText('FIND OWNERS'))->click();

        return new FindOwnersPage($this->driver);
    }
}
