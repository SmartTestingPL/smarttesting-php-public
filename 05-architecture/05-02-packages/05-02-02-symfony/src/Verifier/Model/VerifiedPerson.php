<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Model;

use Symfony\Component\Uid\Uuid;

class VerifiedPerson implements \JsonSerializable
{
    private Uuid $id;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => (string) $this->id,
        ];
    }
}
