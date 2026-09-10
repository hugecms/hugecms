<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'IpBlacklistEntity')]
class IpBlacklistEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getIpAddress = 'ip_address'; // IP地址（支持CIDR格式如：192.168.1.0/24）

    public const string getReason = 'reason'; // 封禁原因

    public const string getBlockUntil = 'block_until'; // 封禁截至时间（NULL表示永久）

    public const string getBlockType = 'block_type'; // 封禁类型：all全部/admin后台/api接口

    public const string getHitCount = 'hit_count'; // 触发次数

    public const string getLastHitAt = 'last_hit_at'; // 最后触发时间

    public const string getOperatorId = 'operator_id'; // 操作人ID

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'ipAddress', description: 'IP地址（支持CIDR格式如：192.168.1.0/24）', type: 'string')]
    private string $ipAddress;

    #[OA\Property(property: 'reason', description: '封禁原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'blockUntil', description: '封禁截至时间（NULL表示永久）', type: 'string')]
    private string $blockUntil;

    #[OA\Property(property: 'blockType', description: '封禁类型：all全部/admin后台/api接口', type: 'string')]
    private string $blockType;

    #[OA\Property(property: 'hitCount', description: '触发次数', type: 'integer')]
    private int $hitCount;

    #[OA\Property(property: 'lastHitAt', description: '最后触发时间', type: 'string')]
    private string $lastHitAt;

    #[OA\Property(property: 'operatorId', description: '操作人ID', type: 'integer')]
    private int $operatorId;

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
     * 获取IP地址（支持CIDR格式如：192.168.1.0/24）
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * 设置IP地址（支持CIDR格式如：192.168.1.0/24）
     */
    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * 获取封禁原因
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * 设置封禁原因
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * 获取封禁截至时间（NULL表示永久）
     */
    public function getBlockUntil(): string
    {
        return $this->blockUntil;
    }

    /**
     * 设置封禁截至时间（NULL表示永久）
     */
    public function setBlockUntil(string $blockUntil): void
    {
        $this->blockUntil = $blockUntil;
    }

    /**
     * 获取封禁类型：all全部/admin后台/api接口
     */
    public function getBlockType(): string
    {
        return $this->blockType;
    }

    /**
     * 设置封禁类型：all全部/admin后台/api接口
     */
    public function setBlockType(string $blockType): void
    {
        $this->blockType = $blockType;
    }

    /**
     * 获取触发次数
     */
    public function getHitCount(): int
    {
        return $this->hitCount;
    }

    /**
     * 设置触发次数
     */
    public function setHitCount(int $hitCount): void
    {
        $this->hitCount = $hitCount;
    }

    /**
     * 获取最后触发时间
     */
    public function getLastHitAt(): string
    {
        return $this->lastHitAt;
    }

    /**
     * 设置最后触发时间
     */
    public function setLastHitAt(string $lastHitAt): void
    {
        $this->lastHitAt = $lastHitAt;
    }

    /**
     * 获取操作人ID
     */
    public function getOperatorId(): int
    {
        return $this->operatorId;
    }

    /**
     * 设置操作人ID
     */
    public function setOperatorId(int $operatorId): void
    {
        $this->operatorId = $operatorId;
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
