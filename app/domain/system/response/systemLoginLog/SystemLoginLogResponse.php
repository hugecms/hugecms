<?php

declare(strict_types=1);

namespace app\domain\system\response\systemLoginLog;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemLoginLogResponse')]
class SystemLoginLogResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userType', description: '账户类型:admin=后台,user=前台', type: 'string')]
    private string $userType;

    #[OA\Property(property: 'userId', description: '用户ID,账号不存在时为空', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'username', description: '登录账号(尝试值快照)', type: 'string')]
    private string $username;

    #[OA\Property(property: 'status', description: '结果:0=失败,1=成功', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'ip', description: '登录IP', type: 'string')]
    private string $ip;

    #[OA\Property(property: 'userAgent', description: 'User-Agent', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
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
