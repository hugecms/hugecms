<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Content;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentResponse')]
class ContentResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'modelId', description: '所属模型ID', type: 'integer')]
    private int $modelId;

    #[OA\Property(property: 'title', description: '内容标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'slug', description: 'URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'authorId', description: '发布者用户ID', type: 'integer')]
    private int $authorId;

    #[OA\Property(property: 'status', description: '状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站', type: 'string')]
    private string $status;

    #[OA\Property(property: 'visibility', description: '可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）', type: 'string')]
    private string $visibility;

    #[OA\Property(property: 'views', description: '浏览量计数', type: 'integer')]
    private int $views;

    #[OA\Property(property: 'commentCount', description: '评论数（审核通过的冗余计数，避免列表页逐条COUNT）', type: 'integer')]
    private int $commentCount;

    #[OA\Property(property: 'sort', description: '手动排序权重（数值越大越靠前）', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'isTop', description: '是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）', type: 'integer')]
    private int $isTop;

    #[OA\Property(property: 'publishedAt', description: '计划/实际发布时间', type: 'string')]
    private string $publishedAt;

    #[OA\Property(property: 'auditStatus', description: '审核状态：pending待审核/approved通过/rejected驳回', type: 'string')]
    private string $auditStatus;

    #[OA\Property(property: 'auditRemark', description: '审核备注（驳回原因）', type: 'string')]
    private string $auditRemark;

    #[OA\Property(property: 'auditorId', description: '审核人ID', type: 'integer')]
    private int $auditorId;

    #[OA\Property(property: 'auditedAt', description: '审核时间', type: 'string')]
    private string $auditedAt;

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
     * 获取所属模型ID
     */
    public function getModelId(): int
    {
        return $this->modelId;
    }

    /**
     * 设置所属模型ID
     */
    public function setModelId(int $modelId): void
    {
        $this->modelId = $modelId;
    }

    /**
     * 获取内容标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置内容标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置URL别名（全局唯一，由应用层从标题生成，避免空串重复占位）
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取发布者用户ID
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * 设置发布者用户ID
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * 获取状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * 设置状态：draft草稿/pending待发布(定时)/published已发布/archived已归档/trash回收站
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * 设置可见性：public公开/password密码保护/private私密（仅登录可见），与 status/audit_status 独立（见 docs/development-conventions.md 状态机）
     */
    public function setVisibility(string $visibility): void
    {
        $this->visibility = $visibility;
    }

    /**
     * 获取浏览量计数
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * 设置浏览量计数
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * 获取评论数（审核通过的冗余计数，避免列表页逐条COUNT）
     */
    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /**
     * 设置评论数（审核通过的冗余计数，避免列表页逐条COUNT）
     */
    public function setCommentCount(int $commentCount): void
    {
        $this->commentCount = $commentCount;
    }

    /**
     * 获取手动排序权重（数值越大越靠前）
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置手动排序权重（数值越大越靠前）
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）
     */
    public function getIsTop(): int
    {
        return $this->isTop;
    }

    /**
     * 设置是否置顶：1置顶（列表排序优先；置顶属列表行为而非内容属性，故为全模型公共列）
     */
    public function setIsTop(int $isTop): void
    {
        $this->isTop = $isTop;
    }

    /**
     * 获取计划/实际发布时间
     */
    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    /**
     * 设置计划/实际发布时间
     */
    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * 获取审核状态：pending待审核/approved通过/rejected驳回
     */
    public function getAuditStatus(): string
    {
        return $this->auditStatus;
    }

    /**
     * 设置审核状态：pending待审核/approved通过/rejected驳回
     */
    public function setAuditStatus(string $auditStatus): void
    {
        $this->auditStatus = $auditStatus;
    }

    /**
     * 获取审核备注（驳回原因）
     */
    public function getAuditRemark(): string
    {
        return $this->auditRemark;
    }

    /**
     * 设置审核备注（驳回原因）
     */
    public function setAuditRemark(string $auditRemark): void
    {
        $this->auditRemark = $auditRemark;
    }

    /**
     * 获取审核人ID
     */
    public function getAuditorId(): int
    {
        return $this->auditorId;
    }

    /**
     * 设置审核人ID
     */
    public function setAuditorId(int $auditorId): void
    {
        $this->auditorId = $auditorId;
    }

    /**
     * 获取审核时间
     */
    public function getAuditedAt(): string
    {
        return $this->auditedAt;
    }

    /**
     * 设置审核时间
     */
    public function setAuditedAt(string $auditedAt): void
    {
        $this->auditedAt = $auditedAt;
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
