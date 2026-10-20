<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\UserFollower;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserFollowerResponse')]
class UserFollowerResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'userId', description: '被关注用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'followerId', description: '关注者用户ID', type: 'integer')]
    private int $followerId;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
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
