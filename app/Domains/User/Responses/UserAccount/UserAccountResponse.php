<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\UserAccount;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAccountResponse')]
class UserAccountResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'balance', description: '账户余额,可为负=欠款', type: 'string')]
    private string $balance;

    #[OA\Property(property: 'frozen', description: '冻结金额,提现审核/打款中在途', type: 'string')]
    private string $frozen;

    #[OA\Property(property: 'available', description: '可用余额=balance-frozen', type: 'string')]
    private string $available;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取账户余额,可为负=欠款
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * 设置账户余额,可为负=欠款
     */
    public function setBalance(string $balance): void
    {
        $this->balance = $balance;
    }

    /**
     * 获取冻结金额,提现审核/打款中在途
     */
    public function getFrozen(): string
    {
        return $this->frozen;
    }

    /**
     * 设置冻结金额,提现审核/打款中在途
     */
    public function setFrozen(string $frozen): void
    {
        $this->frozen = $frozen;
    }

    /**
     * 获取可用余额=balance-frozen
     */
    public function getAvailable(): string
    {
        return $this->available;
    }

    /**
     * 设置可用余额=balance-frozen
     */
    public function setAvailable(string $available): void
    {
        $this->available = $available;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
