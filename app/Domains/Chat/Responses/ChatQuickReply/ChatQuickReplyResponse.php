<?php

declare(strict_types=1);

namespace App\Domains\Chat\Responses\ChatQuickReply;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ChatQuickReplyResponse')]
class ChatQuickReplyResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'group', description: '分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流', type: 'string')]
    private string $group;

    #[OA\Property(property: 'title', description: '话术标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '话术内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'useCount', description: '使用次数', type: 'integer')]
    private int $useCount;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
    private int $status;

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
     * 获取分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * 设置分组:common=通用,pre_sales=售前,after_sales=售后,logistics=物流
     */
    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    /**
     * 获取话术标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置话术标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取话术内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置话术内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取使用次数
     */
    public function getUseCount(): int
    {
        return $this->useCount;
    }

    /**
     * 设置使用次数
     */
    public function setUseCount(int $useCount): void
    {
        $this->useCount = $useCount;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
