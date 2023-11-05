<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Controller;

use SmartTesting\Tests\FunctionalTestCase;
use Symfony\Component\Uid\Uuid;

class FraudControllerTest extends FunctionalTestCase
{
    public function testFraudCheckEndpoint(): void
    {
        $this->client->request('POST', '/api/fraudCheck', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], (string) json_encode([
            'uuid' => (string) Uuid::v4(),
            'person' => [
                'name' => 'Stefan',
                'surname' => 'Fraudowski',
                'dateOfBirth' => '12-12-1988',
                'gender' => 'MALE',
                'nationalIdentificationNumber' => '12234567890',
                'student' => false,
            ],
        ]));

        $this->validateRequest($this->client->getRequest());
        $this->validateResponse($this->client->getRequest(), $this->client->getResponse());

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
