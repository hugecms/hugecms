<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSmsCodeEntity')]
class UserSmsCodeEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getPhone = 'phone'; // 手机号

    public const string getCode = 'code'; // 验证码

    public const string getScene = 'scene'; // 场景:register=注册,login=登录,reset_password=重置密码

    public const string getExpireAt = 'expire_at'; // 过期时间

    public const string getUsedAt = 'used_at'; // 使用时间

    public const string getIp = 'ip'; // 发送IP,用于防刷

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'phone', description: '手机号', type: 'string')]
    private string $phone;

    #[OA\Property(property: 'code', description: '验证码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'scene', description: '场景:register=注册,login=登录,reset_password=重置密码', type: 'string')]
    private string $scene;

    #[OA\Property(property: 'expireAt', description: '过期时间', type: 'string')]
    private string $expireAt;

    #[OA\Property(property: 'usedAt', description: '使用时间', type: 'string')]
    private string $usedAt;

    #[OA\Property(property: 'ip', description: '发送IP,用于防刷', type: 'string')]
    private string $ip;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
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
     * 获取验证码
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置验证码
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取场景:register=注册,login=登录,reset_password=重置密码
     */
    public function getScene(): string
    {
        return $this->scene;
    }

    /**
     * 设置场景:register=注册,login=登录,reset_password=重置密码
     */
    public function setScene(string $scene): void
    {
        $this->scene = $scene;
    }

    /**
     * 获取过期时间
     */
    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    /**
     * 设置过期时间
     */
    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    /**
     * 获取使用时间
     */
    public function getUsedAt(): string
    {
        return $this->usedAt;
    }

    /**
     * 设置使用时间
     */
    public function setUsedAt(string $usedAt): void
    {
        $this->usedAt = $usedAt;
    }

    /**
     * 获取发送IP,用于防刷
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置发送IP,用于防刷
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
