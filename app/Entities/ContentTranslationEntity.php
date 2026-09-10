<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentTranslationEntity')]
class ContentTranslationEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getContentId = 'content_id'; // 关联内容主表ID

    public const string getLocale = 'locale'; // 语言代码（如：zh_CN, en_US）

    public const string getTitle = 'title'; // 翻译后的标题

    public const string getSlug = 'slug'; // 翻译后的URL别名

    public const string getExcerpt = 'excerpt'; // 翻译后的摘要

    public const string getContent = 'content'; // 翻译后的正文内容

    public const string getIsDefault = 'is_default'; // 是否默认语言版本

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contentId', description: '关联内容主表ID', type: 'integer')]
    private int $contentId;

    #[OA\Property(property: 'locale', description: '语言代码（如：zh_CN, en_US）', type: 'string')]
    private string $locale;

    #[OA\Property(property: 'title', description: '翻译后的标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'slug', description: '翻译后的URL别名', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'excerpt', description: '翻译后的摘要', type: 'string')]
    private string $excerpt;

    #[OA\Property(property: 'content', description: '翻译后的正文内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'isDefault', description: '是否默认语言版本', type: 'integer')]
    private int $isDefault;

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
     * 获取关联内容主表ID
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * 设置关联内容主表ID
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * 获取语言代码（如：zh_CN, en_US）
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * 设置语言代码（如：zh_CN, en_US）
     */
    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * 获取翻译后的标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置翻译后的标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取翻译后的URL别名
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置翻译后的URL别名
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取翻译后的摘要
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * 设置翻译后的摘要
     */
    public function setExcerpt(string $excerpt): void
    {
        $this->excerpt = $excerpt;
    }

    /**
     * 获取翻译后的正文内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置翻译后的正文内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取是否默认语言版本
     */
    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    /**
     * 设置是否默认语言版本
     */
    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
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
