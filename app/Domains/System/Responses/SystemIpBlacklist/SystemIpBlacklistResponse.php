<?php

declare(strict_types=1);

namespace App\Domains\System\Responses\SystemIpBlacklist;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemIpBlacklistResponse')]
class SystemIpBlacklistResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'ip', description: 'IP地址,支持IPv4/IPv6', type: 'string')]
    private string $ip;

    #[OA\Property(property: 'reason', description: '拉黑原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'expiredAt', description: '过期时间,空=永久', type: 'string')]
    private string $expiredAt;

    #[OA\Property(property: 'userType', description: '操作账户类型:admin=后台,system=系统', type: 'string')]
    private string $userType;

    #[OA\Property(property: 'userId', description: '操作人ID', type: 'integer')]
    private int $userId;

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
     * 获取IP地址,支持IPv4/IPv6
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置IP地址,支持IPv4/IPv6
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * 获取拉黑原因
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * 设置拉黑原因
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * 获取过期时间,空=永久
     */
    public function getExpiredAt(): string
    {
        return $this->expiredAt;
    }

    /**
     * 设置过期时间,空=永久
     */
    public function setExpiredAt(string $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }

    /**
     * 获取操作账户类型:admin=后台,system=系统
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * 设置操作账户类型:admin=后台,system=系统
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * 获取操作人ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置操作人ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
