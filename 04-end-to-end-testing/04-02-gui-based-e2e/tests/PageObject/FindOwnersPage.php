<?php

declare(strict_types=1);

namespace SmartTesting\Tests\PageObject;

use Facebook\WebDriver\WebDriverBy;

class FindOwnersPage extends PageObject
{
    public function navigateToAddOwner(): AddOwnerPage
    {
        $this->driver->findElement(WebDriverBy::linkText('Add Owner'))->click();

        return new AddOwnerPage($this->driver);
    }
}
