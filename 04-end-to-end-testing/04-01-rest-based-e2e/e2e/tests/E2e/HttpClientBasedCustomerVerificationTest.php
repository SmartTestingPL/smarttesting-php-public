<?php

declare(strict_types=1);

namespace SmartTesting\Tests\E2e;

use SmartTesting\Tests\E2e\Customer\CustomerBuilder;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Przykłady testów E2E po HTTP: gorszy i lepszy.
 */
class HttpClientBasedCustomerVerificationTest extends LoanOrdersHttpClientTestCase
{
    /**
     * Test mało czytelny ze względu na zbyt dużo kodu boiler-plate i mieszanie poziomów
     * abstrakcji, brak sensownej obsługi timeout'ów.
     */
    public function testShouldSetOrderStatusToVerifiedWhenCorrectCustomer(): void
    {
        // given
        $correctCustomer = (new CustomerBuilder())->build();

        $httpClient = HttpClient::create();

        // when
        $response = $httpClient->request('POST', self::LOAN_ORDERS_URI.'/orders', ['json' => $correctCustomer]);

        // then
        self::assertEquals(200, $response->getStatusCode());

        // when
        $orderId = $response->toArray(true)['id'];
        $response = $httpClient->request('GET', self::LOAN_ORDERS_URI.'/orders/'.$orderId);

        // then
        self::assertEquals('verified', $response->toArray(true)['status']);
    }

    public function testShouldSetOrderStatusToFailedWhenInCorrectCustomer(): void
    {
        // given
        $invalidCustomer = (new CustomerBuilder())->withBirthDate('01-01-2019')->build();

        // when
        $loanOrder = $this->createAndRetrieveLoanOrder($invalidCustomer);

        // then
        (new LoanOrderAssert($loanOrder))->customerVerificationFailed();
    }
}
