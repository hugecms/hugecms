<?php

declare(strict_types=1);

namespace app\domain\system\entity;

use Juling\Foundation\Support\ArrayHelper;

class SystemLoginLogEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getUserType = 'user_type'; // 账户类型:admin=后台,user=前台

    public const string getUserId = 'user_id'; // 用户ID,账号不存在时为空

    public const string getUsername = 'username'; // 登录账号(尝试值快照)

    public const string getStatus = 'status'; // 结果:0=失败,1=成功

    public const string getIp = 'ip'; // 登录IP

    public const string getUserAgent = 'user_agent'; // User-Agent

    public const string getCreateTime = 'create_time'; // 

    private int $id;

    private string $userType;

    private int $userId;

    private string $username;

    private int $status;

    private string $ip;

    private string $userAgent;

    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
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
