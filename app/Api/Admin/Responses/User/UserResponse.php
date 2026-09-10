<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\User;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResponse')]
class UserResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '显示昵称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'email', description: '', type: 'string')]
    private string $email;

    #[OA\Property(property: 'emailVerifiedAt', description: '', type: 'string')]
    private string $emailVerifiedAt;

    #[OA\Property(property: 'avatar', description: '头像URL', type: 'string')]
    private string $avatar;

    #[OA\Property(property: 'status', description: '状态：0禁用，1启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'lastLoginIp', description: '最后登录IP（支持IPv6）', type: 'string')]
    private string $lastLoginIp;

    #[OA\Property(property: 'lastLoginTime', description: '最后登录时间', type: 'string')]
    private string $lastLoginTime;

    #[OA\Property(property: 'rememberToken', description: '', type: 'string')]
    private string $rememberToken;

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
     * 获取显示昵称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置显示昵称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取
     */
    public function getEmailVerifiedAt(): string
    {
        return $this->emailVerifiedAt;
    }

    /**
     * 设置
     */
    public function setEmailVerifiedAt(string $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * 获取头像URL
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * 设置头像URL
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * 获取状态：0禁用，1启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0禁用，1启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取最后登录IP（支持IPv6）
     */
    public function getLastLoginIp(): string
    {
        return $this->lastLoginIp;
    }

    /**
     * 设置最后登录IP（支持IPv6）
     */
    public function setLastLoginIp(string $lastLoginIp): void
    {
        $this->lastLoginIp = $lastLoginIp;
    }

    /**
     * 获取最后登录时间
     */
    public function getLastLoginTime(): string
    {
        return $this->lastLoginTime;
    }

    /**
     * 设置最后登录时间
     */
    public function setLastLoginTime(string $lastLoginTime): void
    {
        $this->lastLoginTime = $lastLoginTime;
    }

    /**
     * 获取
     */
    public function getRememberToken(): string
    {
        return $this->rememberToken;
    }

    /**
     * 设置
     */
    public function setRememberToken(string $rememberToken): void
    {
        $this->rememberToken = $rememberToken;
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
