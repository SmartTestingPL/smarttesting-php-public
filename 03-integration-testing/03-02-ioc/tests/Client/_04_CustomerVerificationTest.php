<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use SmartTesting\Client\Person;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class _04_CustomerVerificationTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
    }

    public function testCustomerVerifier(): void
    {
        $customerVerifier = self::$container->get('customerVerifier');

        $result = $customerVerifier->verify($this->stefan());

        self::assertFalse($result->isPassed());
    }

    private function stefan(): Person
    {
        return new Person(Uuid::v4(), '', '', new \DateTimeImmutable(), Person::GENDER_MALE, '');
    }
}
