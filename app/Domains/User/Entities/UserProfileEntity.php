<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserProfileEntity')]
class UserProfileEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getUserId = 'user_id'; // 用户ID

    public const string getNickname = 'nickname'; // 昵称

    public const string getAvatar = 'avatar'; // 头像

    public const string getGender = 'gender'; // 性别:0=未知,1=男,2=女

    public const string getBirthday = 'birthday'; // 生日

    public const string getBio = 'bio'; // 个人简介

    public const string getLocation = 'location'; // 所在地

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'nickname', description: '昵称', type: 'string')]
    private string $nickname;

    #[OA\Property(property: 'avatar', description: '头像', type: 'string')]
    private string $avatar;

    #[OA\Property(property: 'gender', description: '性别:0=未知,1=男,2=女', type: 'integer')]
    private int $gender;

    #[OA\Property(property: 'birthday', description: '生日', type: 'string')]
    private string $birthday;

    #[OA\Property(property: 'bio', description: '个人简介', type: 'string')]
    private string $bio;

    #[OA\Property(property: 'location', description: '所在地', type: 'string')]
    private string $location;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取昵称
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * 设置昵称
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * 获取头像
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * 设置头像
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * 获取性别:0=未知,1=男,2=女
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * 设置性别:0=未知,1=男,2=女
     */
    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    /**
     * 获取生日
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * 设置生日
     */
    public function setBirthday(string $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * 获取个人简介
     */
    public function getBio(): string
    {
        return $this->bio;
    }

    /**
     * 设置个人简介
     */
    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    /**
     * 获取所在地
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * 设置所在地
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
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
}
