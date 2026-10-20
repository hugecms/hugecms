<?php

declare(strict_types=1);

namespace App\Domains\Content\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentCommentLikeEntity')]
class ContentCommentLikeEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getCommentId = 'comment_id'; // 评论ID

    public const string getUserId = 'user_id'; // 点赞用户ID

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'commentId', description: '评论ID', type: 'integer')]
    private int $commentId;

    #[OA\Property(property: 'userId', description: '点赞用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
    private string $createTime;

    /**
     * 获取评论ID
     */
    public function getCommentId(): int
    {
        return $this->commentId;
    }

    /**
     * 设置评论ID
     */
    public function setCommentId(int $commentId): void
    {
        $this->commentId = $commentId;
    }

    /**
     * 获取点赞用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置点赞用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
