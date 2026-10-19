<?php

declare(strict_types=1);

namespace App\Domains\Content\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentCommentEntity')]
class ContentCommentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 评论用户ID

    public const string getTargetType = 'target_type'; // 目标类型:article,product

    public const string getTargetId = 'target_id'; // 目标ID

    public const string getParentId = 'parent_id'; // 父评论ID,0=顶级

    public const string getContent = 'content'; // 评论内容

    public const string getStatus = 'status'; // 状态:0=待审,1=通过,2=拒绝

    public const string getIp = 'ip'; // 评论IP

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '评论用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'targetType', description: '目标类型:article,product', type: 'string')]
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

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

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
     * 获取目标类型:article,product
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * 设置目标类型:article,product
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

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
