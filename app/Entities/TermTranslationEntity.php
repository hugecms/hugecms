<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'TermTranslationEntity')]
class TermTranslationEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getTermId = 'term_id'; // 关联分类项ID

    public const string getLocale = 'locale'; // 语言代码（如：zh_CN, en_US）

    public const string getName = 'name'; // 翻译后的分类项名称

    public const string getSlug = 'slug'; // 翻译后的URL别名

    public const string getDescription = 'description'; // 翻译后的描述

    public const string getIsDefault = 'is_default'; // 是否默认语言版本（与 terms 主表字段同源）

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'termId', description: '关联分类项ID', type: 'integer')]
    private int $termId;

    #[OA\Property(property: 'locale', description: '语言代码（如：zh_CN, en_US）', type: 'string')]
    private string $locale;

    #[OA\Property(property: 'name', description: '翻译后的分类项名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'slug', description: '翻译后的URL别名', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'description', description: '翻译后的描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'isDefault', description: '是否默认语言版本（与 terms 主表字段同源）', type: 'integer')]
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
     * 获取关联分类项ID
     */
    public function getTermId(): int
    {
        return $this->termId;
    }

    /**
     * 设置关联分类项ID
     */
    public function setTermId(int $termId): void
    {
        $this->termId = $termId;
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
     * 获取翻译后的分类项名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置翻译后的分类项名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * 获取翻译后的描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置翻译后的描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取是否默认语言版本（与 terms 主表字段同源）
     */
    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    /**
     * 设置是否默认语言版本（与 terms 主表字段同源）
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
