<?php

declare(strict_types=1);

namespace App\Domains\Email\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'EmailSubscriptionEntity')]
class EmailSubscriptionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getEmail = 'email'; // 订阅邮箱

    public const string getStatus = 'status'; // 状态:0=已退订,1=订阅中

    public const string getToken = 'token'; // 退订令牌

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'email', description: '订阅邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'status', description: '状态:0=已退订,1=订阅中', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'token', description: '退订令牌', type: 'string')]
    private string $token;

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
     * 获取订阅邮箱
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置订阅邮箱
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取状态:0=已退订,1=订阅中
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=已退订,1=订阅中
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取退订令牌
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * 设置退订令牌
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
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
