<?php

declare(strict_types=1);

namespace app\domain\admin\entity;

use Juling\Foundation\Support\ArrayHelper;

class AdminUserEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getUsername = 'username'; // 用户名

    public const string getEmail = 'email'; // 邮箱

    public const string getPhone = 'phone'; // 手机号

    public const string getPassword = 'password'; // 密码哈希

    public const string getStatus = 'status'; // 状态:0=禁用,1=正常

    public const string getLastLoginTime = 'last_login_time'; // 最后登录时间

    public const string getLastLoginIp = 'last_login_ip'; // 最后登录IP

    public const string getCreateTime = 'create_time'; //

    public const string getUpdateTime = 'update_time'; //

    public const string getDeleteTime = 'delete_time'; //

    private int $id;

    private string $username;

    private string $email;

    private string $phone;

    private string $password;

    private int $status;

    private string $lastLoginTime;

    private string $lastLoginIp;

    private string $createTime;

    private string $updateTime;

    private string $deleteTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getLastLoginTime(): string
    {
        return $this->lastLoginTime;
    }

    public function setLastLoginTime(string $lastLoginTime): void
    {
        $this->lastLoginTime = $lastLoginTime;
    }

    public function getLastLoginIp(): string
    {
        return $this->lastLoginIp;
    }

    public function setLastLoginIp(string $lastLoginIp): void
    {
        $this->lastLoginIp = $lastLoginIp;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
