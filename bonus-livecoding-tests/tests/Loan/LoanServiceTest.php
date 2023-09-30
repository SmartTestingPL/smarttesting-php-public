<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Loan;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SmartTesting\Db\MongoDbAccessor;
use SmartTesting\Event\EventEmitter;
use SmartTesting\Loan\LoanCreatedEvent;
use SmartTesting\Loan\LoanService;
use SmartTesting\Loan\Validation\CommissionValidationException;
use SmartTesting\Order\Promotion;

final class LoanServiceTest extends TestCase
{
    private EventEmitter|MockObject $eventEmitter;
    private MongoDbAccessor|MockObject $mongoDbAccessor;
    private LoanService $loanService;

    protected function setUp(): void
    {
        $this->eventEmitter = $this->createMock(EventEmitter::class);
        $this->mongoDbAccessor = $this->createMock(MongoDbAccessor::class);
        $this->loanService = new LoanService($this->eventEmitter, $this->mongoDbAccessor, new TestPostgresAccessor());
        $this->mongoDbAccessor->method('getMinCommission')->willReturn(200.00);
    }

    /**
     * @test
     */
    public function shouldCreateLoan(): void
    {
        $loanOrder = Fixtures::aLoanOrderDefault();

        $loan = $this->loanService->createLoan($loanOrder, 3);

        self::assertEquals(3, $loan->numberOfInstallments());
    }

    /**
     * @test
     */
    public function shouldEmitEventWhenLoanCreated(): void
    {
        $loanOrder = Fixtures::aLoanOrderDefault();

        $this->eventEmitter
            ->expects($this->once())
            ->method('emit')
            ->with($this->isInstanceOf(LoanCreatedEvent::class));

        $this->loanService->createLoan($loanOrder, 3);
    }

    /**
     * @test
     * @dataProvider commissionValues
     */
    public function shouldThrowExceptionWhenIncorrectCommission(float $commission): void
    {
        $this->expectException(CommissionValidationException::class);

        $this->loanService->createLoan(Fixtures::aLoanOrderWith(2000, 5, $commission), 5);
    }

    /**
     * @test
     */
    public function shouldRemoveIncorrectPromotions(): void
    {
        $loanOrder = Fixtures::aLoanOrderWithPromotions(new Promotion('promotion not in DB', 55));

        $this->loanService->updatePromotions($loanOrder);

        self::assertCount(0, $loanOrder->promotions());
    }

    public static function commissionValues(): \Generator
    {
        yield from array_map(fn (int $commission) => [$commission], range(-1, 199));
    }
}
