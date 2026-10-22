<?php

declare(strict_types=1);

namespace App\Domains\Admin\Responses\AdminUser;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminUserResponse')]
class AdminUserResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'username', description: '用户名', type: 'string')]
    private string $username;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'phone', description: '手机号', type: 'string')]
    private string $phone;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=正常', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'isSuper', description: '超级管理员:0=否,1=是;超管不受角色权限限制', type: 'integer')]
    private int $isSuper;

    #[OA\Property(property: 'lastLoginTime', description: '最后登录时间', type: 'string')]
    private string $lastLoginTime;

    #[OA\Property(property: 'lastLoginIp', description: '最后登录IP', type: 'string')]
    private string $lastLoginIp;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

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
     * 获取用户名
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * 设置用户名
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * 获取邮箱
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * 设置邮箱
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取手机号
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * 设置手机号
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * 获取状态:0=禁用,1=正常
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=禁用,1=正常
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取超级管理员:0=否,1=是;超管不受角色权限限制
     */
    public function getIsSuper(): int
    {
        return $this->isSuper;
    }

    /**
     * 设置超级管理员:0=否,1=是;超管不受角色权限限制
     */
    public function setIsSuper(int $isSuper): void
    {
        $this->isSuper = $isSuper;
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
     * 获取最后登录IP
     */
    public function getLastLoginIp(): string
    {
        return $this->lastLoginIp;
    }

    /**
     * 设置最后登录IP
     */
    public function setLastLoginIp(string $lastLoginIp): void
    {
        $this->lastLoginIp = $lastLoginIp;
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

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
