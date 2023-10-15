<?php

declare(strict_types=1);

namespace SmartTesting\Controller;

use SmartTesting\Verifier\Model\VerificationRepository;
use SmartTesting\Verifier\Model\VerifiedPerson;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class VerificationController
{
    private VerificationRepository $repository;

    public function __construct(VerificationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route(path="/verification/{id}", name="find_verificatio", methods={"GET"})
     */
    public function findOrder(string $id): Response
    {
        $person = $this->repository->findById(Uuid::fromString($id));

        return new JsonResponse($person, $person !== null ? 200 : 404);
    }

    /**
     * @Route(path="/verification", name="create_verification", methods={"POST"})
     */
    public function createVerification(Request $request): Response
    {
        return new JsonResponse(['id' => $this->repository->save(new VerifiedPerson(Uuid::v4()))->id()]);
    }
}
