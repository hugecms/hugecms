<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Auth;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AuthMeResponse')]
class MeResponse
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: '管理员ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'username', description: '用户名', type: 'string')]
    private string $username;

    #[OA\Property(property: 'email', description: '邮箱', type: 'string', nullable: true)]
    private ?string $email;

    #[OA\Property(property: 'isSuper', description: '超级管理员:0=否,1=是', type: 'integer')]
    private int $isSuper;

    /**
     * 获取管理员ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置管理员ID
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * 设置邮箱
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * 获取超级管理员:0=否,1=是
     */
    public function getIsSuper(): int
    {
        return $this->isSuper;
    }

    /**
     * 设置超级管理员:0=否,1=是
     */
    public function setIsSuper(int $isSuper): void
    {
        $this->isSuper = $isSuper;
    }
}
