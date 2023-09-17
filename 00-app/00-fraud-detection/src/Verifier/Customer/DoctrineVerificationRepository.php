<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\Uuid;

class DoctrineVerificationRepository implements VerificationRepository
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function findByUserId(Uuid $uuid): ?VerifiedPerson
    {
        return $this->em->getRepository(VerifiedPerson::class)->findOneBy(['userId' => (string) $uuid]);
    }

    public function save(VerifiedPerson $person): void
    {
        $this->em->persist($person);
        $this->em->flush();
    }
}
