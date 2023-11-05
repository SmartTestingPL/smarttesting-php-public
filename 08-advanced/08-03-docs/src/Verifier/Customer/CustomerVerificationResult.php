<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use OpenApi\Annotations as OA;
use Symfony\Component\Uid\Uuid;

/**
 * @OA\Schema(required={"userId", "status"})
 */
class CustomerVerificationResult implements \JsonSerializable
{
    private const VERIFICATION_PASSED = 'passed';
    private const VERIFICATION_FAILED = 'failed';

    private Uuid $userId;
    private string $status;

    private function __construct(Uuid $userId, string $status)
    {
        $this->userId = $userId;
        $this->status = $status;
    }

    public static function passed(Uuid $userId): self
    {
        return new self($userId, self::VERIFICATION_PASSED);
    }

    public static function failed(Uuid $userId): self
    {
        return new self($userId, self::VERIFICATION_FAILED);
    }

    /**
     * @OA\Property(type="string")
     */
    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function jsonSerialize(): array
    {
        return [
            'userId' => $this->userId,
            'status' => $this->status,
        ];
    }
}
