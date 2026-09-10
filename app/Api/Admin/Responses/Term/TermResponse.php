<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Term;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'TermResponse')]
class TermResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'taxonomyId', description: '所属分类法ID', type: 'integer')]
    private int $taxonomyId;

    #[OA\Property(property: 'name', description: '分类项名称（如：科技、体育）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'slug', description: '分类项别名（URL友好）', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'parentId', description: '父级ID（0代表顶级）', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'description', description: '分类项描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'sort', description: '排序权重', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'contentCount', description: '该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）', type: 'integer')]
    private int $contentCount;

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
     * 获取所属分类法ID
     */
    public function getTaxonomyId(): int
    {
        return $this->taxonomyId;
    }

    /**
     * 设置所属分类法ID
     */
    public function setTaxonomyId(int $taxonomyId): void
    {
        $this->taxonomyId = $taxonomyId;
    }

    /**
     * 获取分类项名称（如：科技、体育）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置分类项名称（如：科技、体育）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取分类项别名（URL友好）
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置分类项别名（URL友好）
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取父级ID（0代表顶级）
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID（0代表顶级）
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取分类项描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置分类项描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取排序权重
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序权重
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）
     */
    public function getContentCount(): int
    {
        return $this->contentCount;
    }

    /**
     * 设置该分类下已发布内容数（冗余计数，对标 wp_term_taxonomy.count；仅统计 status=published 且 audit_status=approved；增删关联或发布状态变更时事务维护，见 docs/development-conventions.md）
     */
    public function setContentCount(int $contentCount): void
    {
        $this->contentCount = $contentCount;
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
