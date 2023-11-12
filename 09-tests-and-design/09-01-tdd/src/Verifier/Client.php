<?php

declare(strict_types=1);

namespace App\Verifier;

use Symfony\Component\HttpFoundation\Request;

class Client implements \JsonSerializable
{
    private bool $hasDept;

    public function __construct(bool $hasDept)
    {
        $this->hasDept = $hasDept;
    }

    public function hasDept(): bool
    {
        return $this->hasDept;
    }

    public static function fromRequest(Request $request): self
    {
        $data = json_decode($request->getContent(), true, JSON_THROW_ON_ERROR);

        return new self($data['hasDept']);
    }

    public function jsonSerialize(): array
    {
        return ['hasDept' => $this->hasDept];
    }
}
