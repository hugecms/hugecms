<?php

declare(strict_types=1);

namespace App\Domains\Content\Responses\ContentCategory;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentCategoryResponse')]
class ContentCategoryResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级ID,0=顶级', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '栏目名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'slug', description: '栏目标识,用于URL', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'description', description: '栏目描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'seoTitle', description: 'SEO标题', type: 'string')]
    private string $seoTitle;

    #[OA\Property(property: 'seoKeywords', description: 'SEO关键词', type: 'string')]
    private string $seoKeywords;

    #[OA\Property(property: 'seoDescription', description: 'SEO描述', type: 'string')]
    private string $seoDescription;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer')]
    private int $status;

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
     * 获取父级ID,0=顶级
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID,0=顶级
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取栏目名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置栏目名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取栏目标识,用于URL
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置栏目标识,用于URL
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取栏目描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置栏目描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
