<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Order;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use Symfony\Component\Uid\Uuid;

/**
 * Bazowa klasa testowa, z której dziedziczą klasy testowe. Rozwiązanie polecane
 * szczególnie kiedy mamy wiele klas testowych wymagających takiego samego setupu.
 */
class LoanOrderTestBase extends TestCase
{
    protected function aStudent(): Customer
    {
        $customer = new Customer(Uuid::v4(), new Person('Jan', 'Nowicki', \DateTimeImmutable::createFromFormat('Y-m-d', '1996-08-28'), Person::GENDER_MALE, '96082812079'));
        $customer->student();

        return $customer;
    }

    protected function aCustomer(): Customer
    {
        return new Customer(Uuid::v4(), new Person('Maria', 'Kowalska', \DateTimeImmutable::createFromFormat('Y-m-d', '1989-03-10'), Person::GENDER_MALE, '89031013409'));
    }

    /**
     * Metoda tear-down wywoływana po każdym teście.
     * Oczywiście, można mieć tu jakiś sensowny biznesowy scenariusz zamiast logowania komunikatu.
     * To co chcemy pokazać, to że narzędzia umożliwiają nam wywołanie danej metody po każdym teście.
     */
    protected function tearDown(): void
    {
        fwrite(STDERR, 'Finished test: '.$this->name().PHP_EOL);
    }
}
