<?php

declare(strict_types=1);

namespace SmartTesting\Controller;

use SmartTesting\Order\LoanOrder;
use SmartTesting\Order\LoanOrderService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoanOrderController
{
    private LoanOrderService $loanOrderService;

    public function __construct(LoanOrderService $loanOrderService)
    {
        $this->loanOrderService = $loanOrderService;
    }

    /**
     * @Route(path="/orders/{orderId}", name="find_order", methods={"GET"})
     */
    public function findOrder(string $orderId): Response
    {
        $loanOrder = $this->loanOrderService->findOrder($orderId);

        return new JsonResponse($loanOrder, $loanOrder !== null ? 200 : 404);
    }

    /**
     * @Route(path="/orders", name="create_order", methods={"POST"})
     */
    public function createOrder(LoanOrder $loanOrder): Response
    {
        return new JsonResponse(['id' => $this->loanOrderService->verifyLoanOrder($loanOrder)]);
    }
}
