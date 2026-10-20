<?php

declare(strict_types=1);

namespace App\Domains\Content\Responses\ContentTopic;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentTopicResponse')]
class ContentTopicResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'title', description: '专题标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'slug', description: '专题标识,用于URL', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'cover', description: '专题头图', type: 'string')]
    private string $cover;

    #[OA\Property(property: 'description', description: '专题简介', type: 'string')]
    private string $description;

    #[OA\Property(property: 'content', description: '自定义内容(富文本)', type: 'string')]
    private string $content;

    #[OA\Property(property: 'template', description: '模板标识,空=默认模板', type: 'string')]
    private string $template;

    #[OA\Property(property: 'seoTitle', description: 'SEO标题', type: 'string')]
    private string $seoTitle;

    #[OA\Property(property: 'seoKeywords', description: 'SEO关键词', type: 'string')]
    private string $seoKeywords;

    #[OA\Property(property: 'seoDescription', description: 'SEO描述', type: 'string')]
    private string $seoDescription;

    #[OA\Property(property: 'startTime', description: '上线时间,空=立即', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '下线时间,空=不限', type: 'string')]
    private string $endTime;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=下线,1=上线', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

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
     * 获取专题标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置专题标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取专题标识,用于URL
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置专题标识,用于URL
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取专题头图
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * 设置专题头图
     */
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }

    /**
     * 获取专题简介
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置专题简介
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取自定义内容(富文本)
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置自定义内容(富文本)
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取模板标识,空=默认模板
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * 设置模板标识,空=默认模板
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * 获取SEO标题
     */
    public function getSeoTitle(): string
    {
        return $this->seoTitle;
    }

    /**
     * 设置SEO标题
     */
    public function setSeoTitle(string $seoTitle): void
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * 获取SEO关键词
     */
    public function getSeoKeywords(): string
    {
        return $this->seoKeywords;
    }

    /**
     * 设置SEO关键词
     */
    public function setSeoKeywords(string $seoKeywords): void
    {
        $this->seoKeywords = $seoKeywords;
    }

    /**
     * 获取SEO描述
     */
    public function getSeoDescription(): string
    {
        return $this->seoDescription;
    }

    /**
     * 设置SEO描述
     */
    public function setSeoDescription(string $seoDescription): void
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * 获取上线时间,空=立即
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置上线时间,空=立即
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取下线时间,空=不限
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置下线时间,空=不限
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
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
     * 获取状态:0=下线,1=上线
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=下线,1=上线
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

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
