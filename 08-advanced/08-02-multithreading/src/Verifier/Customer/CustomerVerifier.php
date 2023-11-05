<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use function Amp\Parallel\Worker\enqueue;
use function Amp\Promise\all;
use function Amp\Promise\wait;

use SmartTesting\Customer\Customer;
use SmartTesting\Verifier\Verification;

class CustomerVerifier
{
    /**
     * @var Verification[]
     */
    private array $verifications;

    public function __construct(iterable $verifications)
    {
        foreach ($verifications as $verification) {
            $this->verifications[] = $verification;
        }
    }

    /**
     * @return VerificationResult[]
     */
    public function verify(Customer $customer): array
    {
        $promises = [];
        foreach ($this->verifications as $verification) {
            $promises[] = enqueue(new VerificationTask($verification, $customer->person()));
        }

        return wait(all($promises));
    }

    public function verifyAsync(Customer $customer): void
    {
        foreach ($this->verifications as $verification) {
            enqueue(new VerificationTask($verification, $customer->person()));
        }
    }

    /**
     * @return VerificationResult[]
     */
    public function verifyNoException(Customer $customer): array
    {
        $promises = [];
        foreach ($this->verifications as $verification) {
            $promises[] = enqueue(new VerificationTaskNoException($verification, $customer->person()));
        }

        return wait(all($promises));
    }
}
