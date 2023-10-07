<?php

declare(strict_types=1);

namespace SmartTesting\Tests\E2e;

use SmartTesting\Tests\E2e\Customer\CustomerBuilder;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Przykład wykorzystania generowania danych testowych (faker).
 */
class FakerCustomerVerificationTest extends LoanOrdersHttpClientTestCase
{
    public function testShouldSetOrderStatusToVerifiedWhenCorrectCustomer(): void
    {
        // given
        $adultCustomer = (new CustomerBuilder())->adultMale()->build();

        // when
        $response = HttpClient::create()->request('POST', self::LOAN_ORDERS_URI.'/orders', ['json' => $adultCustomer]);

        // then
        self:self::assertEquals(200, $response->getStatusCode());
    }
}
