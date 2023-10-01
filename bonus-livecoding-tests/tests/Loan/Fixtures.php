<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Loan;

use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Order\LoanOrder;
use SmartTesting\Order\Promotion;
use Symfony\Component\Uid\Uuid;

final class Fixtures
{
    public static function aLoanOrder(float $amount, float $interestRate, float $commission, Promotion ...$promotions): LoanOrder
    {
        $customer = new Customer(
            Uuid::v4(),
            new Person('Maria', 'Kowalska', new \DateTimeImmutable('1989-03-10'), Person::GENDER_FEMALE, '89031013409')
        );
        $loanOrder = new LoanOrder(new \DateTimeImmutable(), $customer, $amount, $interestRate, $commission);
        $loanOrder->setPromotions($promotions);

        return $loanOrder;
    }

    public static function aLoanOrderWith(float $amount, float $interestRate, float $commission): LoanOrder
    {
        return self::aLoanOrder($amount, $interestRate, $commission);
    }

    public static function aLoanOrderDefault(): LoanOrder
    {
        return self::aLoanOrder(2000, 5, 300);
    }

    public static function aLoanOrderWithPromotions(Promotion ...$promotions): LoanOrder
    {
        return self::aLoanOrder(2000, 5, 300, ...$promotions);
    }
}
