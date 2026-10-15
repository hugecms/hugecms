<?php

declare(strict_types=1);

namespace app\domain\user\response\userSmsCode;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSmsCodeResponse')]
class UserSmsCodeResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'phone', description: '手机号', type: 'string')]
    private string $phone;

    #[OA\Property(property: 'code', description: '验证码', type: 'string')]
    private string $code;

    #[OA\Property(property: 'scene', description: '场景:register,login,reset_password', type: 'string')]
    private string $scene;

    #[OA\Property(property: 'expireAt', description: '过期时间', type: 'string')]
    private string $expireAt;

    #[OA\Property(property: 'usedAt', description: '使用时间', type: 'string')]
    private string $usedAt;

    #[OA\Property(property: 'ip', description: '发送IP,用于防刷', type: 'string')]
    private string $ip;

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
