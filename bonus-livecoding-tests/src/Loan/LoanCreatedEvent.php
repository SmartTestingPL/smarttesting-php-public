<?php

declare(strict_types=1);

namespace SmartTesting\Loan;

use SmartTesting\Event\Event;
use Symfony\Component\Uid\Uuid;

final class LoanCreatedEvent extends Event
{
    private Uuid $loanUuid;

    public function __construct(Uuid $loanUuid)
    {
        $this->loanUuid = $loanUuid;
    }

    public function loanUuid(): Uuid
    {
        return $this->loanUuid;
    }
}
