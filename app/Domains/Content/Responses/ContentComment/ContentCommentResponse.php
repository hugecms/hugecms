<?php

declare(strict_types=1);

namespace App\Domains\Content\Responses\ContentComment;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentCommentResponse')]
class ContentCommentResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '评论用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'targetType', description: '目标类型:article=文章;商品评价走product_reviews表', type: 'string')]
    private string $targetType;

    #[OA\Property(property: 'targetId', description: '目标ID', type: 'integer')]
    private int $targetId;

    #[OA\Property(property: 'parentId', description: '父评论ID,0=顶级', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'content', description: '评论内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'status', description: '状态:0=待审,1=通过,2=拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'ip', description: '评论IP', type: 'string')]
    private string $ip;

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
     * 获取评论用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置评论用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取目标类型:article=文章;商品评价走product_reviews表
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * 设置目标类型:article=文章;商品评价走product_reviews表
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * 获取目标ID
     */
    public function getTargetId(): int
    {
        return $this->targetId;
    }

    /**
     * 设置目标ID
     */
    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
    }

    /**
     * 获取父评论ID,0=顶级
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父评论ID,0=顶级
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取评论内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置评论内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取状态:0=待审,1=通过,2=拒绝
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待审,1=通过,2=拒绝
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取评论IP
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置评论IP
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
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
