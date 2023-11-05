<?php

declare(strict_types=1);

namespace SmartTesting\Controller;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Customer\CustomerVerificationResult;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FraudController
{
    private CustomerVerifier $verifier;

    public function __construct(CustomerVerifier $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * @Route(path="/api/fraudCheck", methods={"POST"}, name="verify_customer")
     *
     * @OA\RequestBody(
     *
     *     @Model(type=Customer::class)
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns verification result",
     *
     *     @OA\JsonContent(
     *        ref=@Model(type=CustomerVerificationResult::class)
     *     )
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     *
     * @OA\Tag(name="Customer")
     */
    public function verify(Customer $customer): Response
    {
        return new JsonResponse($this->verifier->verify($customer));
    }
}
