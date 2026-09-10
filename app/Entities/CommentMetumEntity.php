<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommentMetumEntity')]
class CommentMetumEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getCommentId = 'comment_id'; // 关联评论ID

    public const string getMetaKey = 'meta_key'; // 元数据键名（如 akismet_score）

    public const string getMetaValue = 'meta_value'; // 元数据值

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'commentId', description: '关联评论ID', type: 'integer')]
    private int $commentId;

    #[OA\Property(property: 'metaKey', description: '元数据键名（如 akismet_score）', type: 'string')]
    private string $metaKey;

    #[OA\Property(property: 'metaValue', description: '元数据值', type: 'string')]
    private string $metaValue;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

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
     * 获取关联评论ID
     */
    public function getCommentId(): int
    {
        return $this->commentId;
    }

    /**
     * 设置关联评论ID
     */
    public function setCommentId(int $commentId): void
    {
        $this->commentId = $commentId;
    }

    /**
     * 获取元数据键名（如 akismet_score）
     */
    public function getMetaKey(): string
    {
        return $this->metaKey;
    }

    /**
     * 设置元数据键名（如 akismet_score）
     */
    public function setMetaKey(string $metaKey): void
    {
        $this->metaKey = $metaKey;
    }

    /**
     * 获取元数据值
     */
    public function getMetaValue(): string
    {
        return $this->metaValue;
    }

    /**
     * 设置元数据值
     */
    public function setMetaValue(string $metaValue): void
    {
        $this->metaValue = $metaValue;
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
