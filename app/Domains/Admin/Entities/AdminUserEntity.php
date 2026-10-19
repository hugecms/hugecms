<?php

declare(strict_types=1);

namespace App\Domains\Admin\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminUserEntity')]
class AdminUserEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUsername = 'username'; // 用户名

    public const string getEmail = 'email'; // 邮箱

    public const string getPhone = 'phone'; // 手机号

    public const string getPassword = 'password'; // 密码哈希

    public const string getStatus = 'status'; // 状态:0=禁用,1=正常

    public const string getLastLoginTime = 'last_login_time'; // 最后登录时间

    public const string getLastLoginIp = 'last_login_ip'; // 最后登录IP

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    public const string getDeleteTime = 'delete_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'username', description: '用户名', type: 'string')]
    private string $username;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string')]
    private string $email;

    #[OA\Property(property: 'phone', description: '手机号', type: 'string')]
    private string $phone;

    #[OA\Property(property: 'password', description: '密码哈希', type: 'string')]
    private string $password;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=正常', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'lastLoginTime', description: '最后登录时间', type: 'string')]
    private string $lastLoginTime;

    #[OA\Property(property: 'lastLoginIp', description: '最后登录IP', type: 'string')]
    private string $lastLoginIp;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

    #[OA\Property(property: 'deleteTime', description: '', type: 'string')]
    private string $deleteTime;

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
     * 获取密码哈希
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * 设置密码哈希
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
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
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    /**
     * 获取
     */
    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    /**
     * 设置
     */
    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
