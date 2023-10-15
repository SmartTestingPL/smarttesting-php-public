<?php

declare(strict_types=1);

namespace SmartTesting\Tests\Benchmark;

use Psr\Log\NullLogger;
use SmartTesting\Customer\Customer;
use SmartTesting\Customer\Person;
use SmartTesting\Verifier\Customer\CustomerVerifier;
use SmartTesting\Verifier\Customer\InMemoryVerificationRepository;
use Symfony\Component\Uid\Uuid;

/**
 * @BeforeMethods({"init"})
 */
class CustomerVerifierBench
{
    private CustomerVerifier $verifier;

    public function init(): void
    {
        $this->verifier = new CustomerVerifier([], new InMemoryVerificationRepository(), new NullLogger());
    }

    /**
     * Microbenchmark dla CustomerVerifier
     * warmup - robimy na rozgrzewkę 2 rewizje (dwa wywołania funkcji)
     * iteration - ilość iteracji, w każdej iteracji robimy x rewizji
     * revs - rewizje, czyli pojedyncze wywołanie funkcji
     * OutputTimeUnit - w raporcie jednostką czasu ma być milisekunda
     * OutputMode - w raporcie zamiast czasu chcemy zobacz możliwą ilość operacji w danej jednostce czas.
     *
     * czyli poniższy przykład pokaże w raporcie ile razy w ciągu jednej milisekundy można wywołać funkcję verify
     * przykładowo: 68.244ops/ms.
     *
     * @Warmup(2)
     *
     * @Iterations(3)
     *
     * @Revs(100)
     *
     * @OutputTimeUnit("milliseconds")
     *
     * @OutputMode("throughput")
     */
    public function benchVerify(): void
    {
        $this->verifier->verify($this->customer());
    }

    private function customer(): Customer
    {
        return new Customer(Uuid::v4(), new Person('John', 'Doe', new \DateTimeImmutable(), 'male', '123456789'));
    }
}
