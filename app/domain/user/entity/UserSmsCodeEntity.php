<?php

declare(strict_types=1);

namespace app\domain\user\entity;

use Juling\Foundation\Support\ArrayHelper;

class UserSmsCodeEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getPhone = 'phone'; // 手机号

    public const string getCode = 'code'; // 验证码

    public const string getScene = 'scene'; // 场景:register,login,reset_password

    public const string getExpireAt = 'expire_at'; // 过期时间

    public const string getUsedAt = 'used_at'; // 使用时间

    public const string getIp = 'ip'; // 发送IP,用于防刷

    public const string getCreateTime = 'create_time'; //

    private int $id;

    private string $phone;

    private string $code;

    private string $scene;

    private string $expireAt;

    private string $usedAt;

    private string $ip;

    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getScene(): string
    {
        return $this->scene;
    }

    public function setScene(string $scene): void
    {
        $this->scene = $scene;
    }

    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    public function getUsedAt(): string
    {
        return $this->usedAt;
    }

    public function setUsedAt(string $usedAt): void
    {
        $this->usedAt = $usedAt;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
