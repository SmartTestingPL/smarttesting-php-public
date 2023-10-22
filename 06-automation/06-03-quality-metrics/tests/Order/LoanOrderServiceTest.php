<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Order;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Order\LoanOrderService;
use Symfony\Component\Uid\Uuid;

class LoanOrderServiceTest extends TestCase
{
    private LoanOrderService $loanOrderService;

    protected function setUp(): void
    {
        $this->loanOrderService = new LoanOrderService();
    }

    /**
     * Wywołanie metody setupującej w teście.
     *
     * @test
     */
    public function shouldCreateStudentLoanOrder(): void
    {
        $loanOrder = $this->loanOrderService->studentLoanOrder($this->aStudent());

        self::assertCount(1, $loanOrder->promotions());
        self::assertCount(1, array_filter($loanOrder->promotions(), fn ($promotion) => $promotion->name() === 'Student Promo'));
        self::assertEquals(10.0, $loanOrder->promotions()[0]->discount());
        self::assertEquals((new \DateTimeImmutable())->setTime(0, 0, 0, 0), $loanOrder->orderDate());
    }

    /**
     * Przykład AssertObject Pattern.
     *
     * @test
     */
    public function assertObjectShouldCreateStudentLoanOrder(): void
    {
        $loanOrder = $this->loanOrderService->studentLoanOrder($this->aStudent());

        $orderAssert = new LoanOrderAssert($loanOrder);
        $orderAssert->registeredToday();
        $orderAssert->hasPromotion('Student Promo');
        $orderAssert->hasOnlyOnePromotion();
        $orderAssert->firstPromotionHasDiscountValue(10.0);
    }

    /**
     * Przykład AssertObject Pattern z chainowaniem asercji.
     *
     * @test
     */
    public function chainedAssertObjectShouldCreateStudentLoanOrder(): void
    {
        $loanOrder = $this->loanOrderService->studentLoanOrder($this->aStudent());

        LoanOrderAssert::then($loanOrder)
            ->registeredToday()
            ->hasOnlyOnePromotion()
            ->firstPromotionHasDiscountValue(10.0)
        ;
    }

    /**
     * Przykład AssertObject Pattern z zastosowaniem metody wrappującej chain asercji.
     *
     * @test
     */
    public function chainedAssertObjectShouldCreateStudentLoanOrderSimpleAssertion(): void
    {
        $loanOrder = $this->loanOrderService->studentLoanOrder($this->aStudent());

        LoanOrderAssert::then($loanOrder)->correctStudentLoadOrder();
    }

    /**
     * Metoda zawierająca setup klientów typu STUDENT na potrzeby testów.
     */
    private function aStudent(): Customer
    {
        $customer = new Customer(
            Uuid::v4(),
            new Person(
                'John',
                'Smith',
                \DateTimeImmutable::createFromFormat('Y-m-d', '1996-08-28'),
                Person::GENDER_MALE, '96082812079'
            )
        );
        $customer->student();

        return $customer;
    }
}
