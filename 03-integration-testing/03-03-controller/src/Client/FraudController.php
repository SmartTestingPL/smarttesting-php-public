<?php

declare(strict_types=1);

namespace SmartTesting\Client;

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
     * @Route(path="/fraudCheck", name="fraud_check", methods={"POST"})
     */
    public function fraudCheck(Person $person): Response
    {
        if ($this->verifier->verify($person)->isPassed()) {
            return new JsonResponse(null, Response::HTTP_OK);
        }

        return new JsonResponse(null, Response::HTTP_UNAUTHORIZED);
    }
}
