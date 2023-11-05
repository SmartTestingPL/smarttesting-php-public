<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Verifier\Customer;

use PHPUnit\Framework\TestCase;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\Verification\AgeVerification;
use SmartTesting\Verifier\Customer\Verification\IdentificationNumberVerification;
use SmartTesting\Verifier\Customer\Verification\NameVerification;
use SmartTesting\Verifier\Customer\VerificationEventPublisher;
use SmartTesting\Verifier\Customer\VerificationResult;
use STS\Backoff\Backoff;
use Symfony\Component\Uid\Uuid;

class _01_CustomerVerifierTest extends TestCase
{
    private CustomerVerifier $verifier;
    private VerificationEventPublisher $publisher;

    protected function setUp(): void
    {
        $this->publisher = new VerificationEventPublisher();

        $this->verifier = new CustomerVerifier([
            new AgeVerification($this->publisher),
            new IdentificationNumberVerification($this->publisher),
            new NameVerification($this->publisher),
        ]);

        // dla celów testowych tworzymy katalog gdzie zapisywane będą eventy
        system('mkdir -p /tmp/customer-events');
    }

    protected function tearDown(): void
    {
        // czyścimy po testach
        system('rm -rf /tmp/customer-events/*');
    }

    /**
     * W przypadku PHP nie mamy tego problemu co w Javie, każdy promise zwracany jest w kolejności dodania
     * problem pojawił by się w przypadku wykorzystania `first` zamiast `all`, w takim przypadku moglibyśmy
     * wykorzystać asercję `assertContains` lub po prostu `assertTrue(in_array)`.
     */
    public function test_should_return_results_in_order_of_execution(): void
    {
        self::assertEquals(
            ['age', 'id', 'name'],
            array_map(
                fn (VerificationResult $result) => $result->name(),
                $this->verifier->verify(new Customer(Uuid::v4(), $this->tooYoungStefan()))
            )
        );
    }

    public function test_should_work_in_parallel_without_a_sleep(): void
    {
        $this->markTestSkipped('Ten test nie przejdzie');

        $this->verifier->verifyAsync(new Customer(Uuid::v4(), $this->tooYoungStefan()));

        $events = $this->publisher->events();
        self::assertContains('age', $events);
        self::assertContains('id', $events);
        self::assertContains('name', $events);
    }

    /**
     * Próba naprawy sytuacji z testu powyżej.
     *
     * Zakładamy, że w ciągu 4 sekund zadania powinny się ukończyć, a zdarzenia powinny zostać wysłane.
     *
     * Rozwiązanie to w żaden sposób się nie skaluje i jest marnotrawstwem czasu. W momencie, w którym
     * procesowanie ukończy się po np. 100 ms, zmarnujemy 3.9 sekundy by dokonać asercji.
     */
    public function test_should_work_in_parallel_with_a_sleep(): void
    {
        $this->verifier->verify(new Customer(Uuid::v4(), $this->tooYoungStefan()));

        sleep(4);

        $events = $this->publisher->events();
        self::assertContains('age', $events);
        self::assertContains('id', $events);
        self::assertContains('name', $events);
    }

    /**
     * Najlepsze rozwiązanie problemu.
     *
     * Wykorzystujemy bibliotekę Backoff, która stosuje polling - czyli poczeka maksymalnie 5 sekund
     * i będzie co 100 milisekund weryfikować rezultat asercji. W ten sposób maksymalnie będziemy spóźnieni
     * wobec uzyskanych wyników o ok. 100 ms.
     */
    public function test_should_work_in_parallel_with_backoff(): void
    {
        $this->verifier->verify(new Customer(Uuid::v4(), $this->tooYoungStefan()));

        // ConstantStrategy with defaults, effectively giving you a 100 millisecond sleep time.
        $backoff = new Backoff(null, 'constant', 5, null, function ($attempt, $maxAttempts, array $events, $exception = null) {
            return count($events) !== 3 || $exception !== null;
        });

        $events = $backoff->run(function () {
            return $this->publisher->events();
        });
        self::assertContains('age', $events);
        self::assertContains('id', $events);
        self::assertContains('name', $events);
    }

    private function tooYoungStefan(): Person
    {
        return new Person('', '', (new \DateTimeImmutable())->modify('-1 year'), Person::GENDER_MALE, '0123456789');
    }
}
