<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Order;

use SmartTesting\Order\LoanOrderService;
use SmartTesting\Order\MongoDbAccessor;
use SmartTesting\Order\PostgresAccessor;

/**
 * Klasa zawiera przykłady różnych sposobów setupu i tear-downu testów
 * i przykłady zastosowania stubów i mocków.
 */
class LoanOrderServiceTest extends LoanOrderTestBase
{
    private LoanOrderService $loanOrderService;
    private PostgresAccessor $postgresAccessor;
    private MongoDbAccessor $mongoDbAccessor;

    /**
     * metoda setupująca, drugi sposób to metoda publiczna z adnotacją @before
     * więcej o adnotacjach można znaleźć pod adresem https://phpunit.readthedocs.io/en/9.3/annotations.html.
     */
    protected function setUp(): void
    {
        // Mock, który będzie wykorzystywany później do weryfikacji interakcji
        $this->postgresAccessor = $this->createMock(PostgresAccessor::class);

        // Tworzenie obiektów stub/ mock
        // Ten obiekt tak naprawdę jest wyłącznie stubem (nie używamy go do weryfikacji interakcji),
        // a to, że jest tworzony metodą `createMock(...)` to wyłącznie specyfika frameworku.
        $this->mongoDbAccessor = $this->createMock(MongoDbAccessor::class);

        $this->loanOrderService = new LoanOrderService($this->postgresAccessor, $this->mongoDbAccessor);

        // stubowanie metody getPromotionDiscount
        $this->mongoDbAccessor->method('getPromotionDiscount')->willReturn(10.0);
    }

    public function testShouldCreateStudentLoanOrder(): void
    {
        $loanOrder = $this->loanOrderService->studentLoanOrder($this->aStudent());

        self::assertCount(1, $loanOrder->promotions());
        self::assertCount(1, array_filter($loanOrder->promotions(), fn ($promotion) => $promotion->name() === 'Student Promo'));
        self::assertEquals(10.0, $loanOrder->promotions()[0]->discount());
        self::assertEquals((new \DateTimeImmutable())->setTime(0, 0, 0, 0), $loanOrder->orderDate());
    }

    public function testShouldUpdatePromotionStatistics(): void
    {
        // weryfikacja interakcji z stub z zadanymi parametrami
        $this->postgresAccessor->expects($this->once())->method('updatePromotionStatistics')->with('Student Promo');

        // weryfikacja tego, że dana interakcja nie wystąpiła
        $this->postgresAccessor->expects($this->never())->method('updatePromotionDiscount')->with('Student Promo', $this->any());

        $this->loanOrderService->studentLoanOrder($this->aStudent());
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
}
