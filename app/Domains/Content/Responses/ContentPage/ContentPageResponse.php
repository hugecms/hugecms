<?php

declare(strict_types=1);

namespace App\Domains\Content\Responses\ContentPage;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentPageResponse')]
class ContentPageResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'title', description: '页面标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'slug', description: '页面标识,如:about', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'content', description: '页面内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'seoTitle', description: 'SEO标题', type: 'string')]
    private string $seoTitle;

    #[OA\Property(property: 'seoKeywords', description: 'SEO关键词', type: 'string')]
    private string $seoKeywords;

    #[OA\Property(property: 'seoDescription', description: 'SEO描述', type: 'string')]
    private string $seoDescription;

    #[OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer')]
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
     * 获取页面标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置页面标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取页面标识,如:about
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置页面标识,如:about
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取页面内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置页面内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
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
     * 获取状态:0=隐藏,1=显示
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=隐藏,1=显示
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
