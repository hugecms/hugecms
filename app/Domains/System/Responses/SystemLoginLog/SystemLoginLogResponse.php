<?php

declare(strict_types=1);

namespace App\Domains\System\Responses\SystemLoginLog;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemLoginLogResponse')]
class SystemLoginLogResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

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
     * 获取账户类型:admin=后台,user=前台
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * 设置账户类型:admin=后台,user=前台
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * 获取用户ID,账号不存在时为空
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID,账号不存在时为空
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取登录账号(尝试值快照)
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * 设置登录账号(尝试值快照)
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * 获取结果:0=失败,1=成功
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置结果:0=失败,1=成功
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取登录IP
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置登录IP
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * 获取User-Agent
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置User-Agent
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
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
}
