<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Testujemy czy nasza aplikacja działa poprawnie (logicznie).
 */
class VerificationControllerTest extends WebTestCase
{
    public function testCreateVerification(): void
    {
        $client = static::createClient();
        $client->request('POST', '/verification');

        self::assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);
        $client->request('GET', '/verification/'.$data['id']);

        self::assertResponseStatusCodeSame(200);
    }
}
