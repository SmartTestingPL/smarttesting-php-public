<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use PHPUnit\Framework\TestCase;

class BrokenDomainTest extends TestCase
{
    /**
     * Tu mamy przykład jak w kodzie backendowym możemy tworzyć elementy UI.
     * Niestety, skoro w tym samym kodzie mamy dostęp do innych komponentów,
     * np. do repozytoriów nad bazami danych. Nic nie szkodzi na przeszkodzie,
     * żeby z kodu kliknięcia w przycisk uruchomić jakieś zapytanie SQL...
     */
    public function test_should_present_broken_domain(): void
    {
        $ui = new UI(new Button(new Repository()));
        if ($ui->userClicked()) {
            $loan = $ui->button()->repository()->save(new Loan());
            $ui->button()->showInUi($loan->client()->marketing()->homeAddress());
        }

        // zaspokajamy phpunit (incomplete test)
        self::assertTrue(true);
    }
}

class UI
{
    private Button $button;

    public function __construct(Button $button)
    {
        $this->button = $button;
    }

    public function userClicked(): bool
    {
        return true;
    }

    public function button(): Button
    {
        return $this->button;
    }
}

class Button
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function showInUi(HomeAddress $address): void
    {
    }

    public function repository(): Repository
    {
        return $this->repository;
    }
}

class Discounts
{
}

class HomeAddress
{
}

class Marketing
{
    private HomeAddress $homeAddress;

    public function __construct()
    {
        $this->homeAddress = new HomeAddress();
    }

    public function homeAddress(): HomeAddress
    {
        return $this->homeAddress;
    }
}

class Client
{
    private Marketing $marketing;

    public function __construct()
    {
        $this->marketing = new Marketing();
    }

    public function marketing(): Marketing
    {
        return $this->marketing;
    }
}

class Loan
{
    private Client $client;
    private Marketing $marketing;
    private Discounts $discounts;

    public function __construct()
    {
        $this->client = new Client();
        $this->marketing = new Marketing();
        $this->discounts = new Discounts();
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function marketing(): Marketing
    {
        return $this->marketing;
    }

    public function discounts(): Discounts
    {
        return $this->discounts;
    }
}

class Repository
{
    public function save(Loan $loan): Loan
    {
        return $loan;
    }
}
