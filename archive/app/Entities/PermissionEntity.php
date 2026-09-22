<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PermissionEntity')]
class PermissionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getParentId = 'parent_id'; // 父级权限ID（0表示顶级）

    public const string getName = 'name'; // 权限名称（如：文章编辑）

    public const string getCode = 'code'; // 权限代码（如：content:article:edit）

    public const string getModule = 'module'; // 所属模块（分组展示用）

    public const string getDescription = 'description'; // 权限描述

    public const string getSort = 'sort'; // 排序

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级权限ID（0表示顶级）', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '权限名称（如：文章编辑）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '权限代码（如：content:article:edit）', type: 'string')]
    private string $code;

    #[OA\Property(property: 'module', description: '所属模块（分组展示用）', type: 'string')]
    private string $module;

    #[OA\Property(property: 'description', description: '权限描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

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
     * 获取父级权限ID（0表示顶级）
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级权限ID（0表示顶级）
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取权限名称（如：文章编辑）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置权限名称（如：文章编辑）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取权限代码（如：content:article:edit）
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置权限代码（如：content:article:edit）
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取所属模块（分组展示用）
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * 设置所属模块（分组展示用）
     */
    public function setModule(string $module): void
    {
        $this->module = $module;
    }

    /**
     * 获取权限描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置权限描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
