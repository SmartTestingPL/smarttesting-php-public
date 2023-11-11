<?php

declare(strict_types=1);

namespace App\Controller;

use App\Verifier\Client;
use App\Verifier\FraudVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FraudController extends AbstractController
{
    private FraudVerifier $verifier;

    public function __construct(FraudVerifier $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * @Route("/fraudcheck", methods={"POST"})
     */
    public function fraud(Request $request): JsonResponse
    {
        $client = Client::fromRequest($request);

        return new JsonResponse($this->verifier->verify($client));
    }
}
