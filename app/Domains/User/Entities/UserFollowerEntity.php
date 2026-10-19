<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserFollowerEntity')]
class UserFollowerEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getUserId = 'user_id'; // 被关注用户ID

    public const string getFollowerId = 'follower_id'; // 关注者用户ID

    public const string getCreateTime = 'create_time';

    #[OA\Property(property: 'userId', description: '被关注用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'followerId', description: '关注者用户ID', type: 'integer')]
    private int $followerId;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    /**
     * 获取被关注用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置被关注用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取关注者用户ID
     */
    public function getFollowerId(): int
    {
        return $this->followerId;
    }

    /**
     * 设置关注者用户ID
     */
    public function setFollowerId(int $followerId): void
    {
        $this->followerId = $followerId;
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
