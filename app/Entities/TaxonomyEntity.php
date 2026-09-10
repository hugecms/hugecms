<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'TaxonomyEntity')]
class TaxonomyEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 分类法名称（如：文章分类、产品系列）

    public const string getAlias = 'alias'; // 分类法别名（如：article_cat）

    public const string getModelId = 'model_id'; // 绑定的模型ID（NULL表示全局分类）

    public const string getIsHierarchical = 'is_hierarchical'; // 是否支持层级：1是（分类目录），0否（标签）

    public const string getDescription = 'description'; // 描述

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '分类法名称（如：文章分类、产品系列）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'alias', description: '分类法别名（如：article_cat）', type: 'string')]
    private string $alias;

    #[OA\Property(property: 'modelId', description: '绑定的模型ID（NULL表示全局分类）', type: 'integer')]
    private int $modelId;

    #[OA\Property(property: 'isHierarchical', description: '是否支持层级：1是（分类目录），0否（标签）', type: 'integer')]
    private int $isHierarchical;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    private string $description;

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
     * 获取分类法名称（如：文章分类、产品系列）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置分类法名称（如：文章分类、产品系列）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取分类法别名（如：article_cat）
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * 设置分类法别名（如：article_cat）
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * 获取绑定的模型ID（NULL表示全局分类）
     */
    public function getModelId(): int
    {
        return $this->modelId;
    }

    /**
     * 设置绑定的模型ID（NULL表示全局分类）
     */
    public function setModelId(int $modelId): void
    {
        $this->modelId = $modelId;
    }

    /**
     * 获取是否支持层级：1是（分类目录），0否（标签）
     */
    public function getIsHierarchical(): int
    {
        return $this->isHierarchical;
    }

    /**
     * 设置是否支持层级：1是（分类目录），0否（标签）
     */
    public function setIsHierarchical(int $isHierarchical): void
    {
        $this->isHierarchical = $isHierarchical;
    }

    /**
     * 获取描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
