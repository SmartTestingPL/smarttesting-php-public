<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class _04_WebFraudControllerTest extends WebTestCase
{
    public function testShouldRejectLoanApplicationWhenPersonTooYoung(): void
    {
        $client = static::createClient();
        $client->request('POST', '/fraudCheck', [], [], [], json_encode($this->tooYoungZbigniew()));

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    private function tooYoungZbigniew(): array
    {
        return [
            'uuid' => '7b3e02b3-6b1a-4e75-bdad-cef5b279b074',
            'name' => 'Zbigniew',
            'surname' => 'Zamłodowski',
            'dateOfBirth' => '2020-01-01',
            'gender' => 'male',
            'nationalIdentificationNumber' => '18210116954',
        ];
    }
}
