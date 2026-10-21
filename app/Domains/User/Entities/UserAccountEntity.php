<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAccountEntity')]
class UserAccountEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getBalance = 'balance'; // 账户余额,可为负=欠款,欠款不可用于消费/提现

    public const string getFrozen = 'frozen'; // 冻结金额,提现审核/打款中在途

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'balance', description: '账户余额,可为负=欠款,欠款不可用于消费/提现', type: 'string')]
    private string $balance;

    #[OA\Property(property: 'frozen', description: '冻结金额,提现审核/打款中在途', type: 'string')]
    private string $frozen;

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
     * 获取账户余额,可为负=欠款,欠款不可用于消费/提现
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * 设置账户余额,可为负=欠款,欠款不可用于消费/提现
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
