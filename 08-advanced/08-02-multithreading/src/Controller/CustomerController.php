<?php

declare(strict_types=1);

namespace SmartTesting\Controller;

use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController
{
    private CustomerVerifier $verifier;

    public function __construct(CustomerVerifier $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * @Route(path="/customers", methods={"POST"}, name="verify_customer")
     */
    public function verify(Customer $customer): Response
    {
        return new JsonResponse($this->verifier->verify($customer));
    }
}
