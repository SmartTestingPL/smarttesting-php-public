<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use PHPUnit\Framework\TestCase;
use SmartTesting\Client\AgeVerification;
use SmartTesting\Client\CustomerVerifier;
use SmartTesting\Client\FraudController;
use SmartTesting\Client\Person;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class _01_ControllerTest extends TestCase
{
    public function testShouldRejectLoanApplicationWhenPersonTooYoung(): void
    {
        $controller = new FraudController(new CustomerVerifier([new AgeVerification()]));

        $response = $controller->fraudCheck($this->tooYoungZbigniew());

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    private function tooYoungZbigniew(): Person
    {
        return new Person(Uuid::v4(), '', '', new \DateTimeImmutable(), Person::GENDER_MALE, '');
    }
}
