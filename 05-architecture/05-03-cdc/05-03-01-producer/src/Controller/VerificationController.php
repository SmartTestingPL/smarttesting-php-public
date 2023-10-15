<?php

declare(strict_types=1);

namespace SmartTesting\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerificationController
{
    /**
     * @Route(path="/fraudCheck", methods={"POST"})
     */
    public function verify(Request $request): Response
    {
        $request = json_decode($request->getContent(), true);
        if (!isset($request['dateOfBirth'])) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        $age = \DateTimeImmutable::createFromFormat('Y-m-d', $request['dateOfBirth'])->diff(new \DateTimeImmutable())->y;

        return new JsonResponse(null, $age >= 18 && $age <= 99 ? Response::HTTP_OK : Response::HTTP_UNAUTHORIZED);
    }
}
