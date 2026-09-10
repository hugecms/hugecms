<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'DepartmentEntity')]
class DepartmentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getParentId = 'parent_id'; // 父级部门ID（0表示顶级）

    public const string getName = 'name'; // 部门名称（如：产品中心、技术研发部）

    public const string getCode = 'code'; // 部门编码（如：PD、RD）

    public const string getLeaderId = 'leader_id'; // 部门负责人ID

    public const string getDescription = 'description'; // 部门描述

    public const string getSort = 'sort'; // 排序权重

    public const string getStatus = 'status'; // 状态：0停用，1启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级部门ID（0表示顶级）', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '部门名称（如：产品中心、技术研发部）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '部门编码（如：PD、RD）', type: 'string')]
    private string $code;

    #[OA\Property(property: 'leaderId', description: '部门负责人ID', type: 'integer')]
    private int $leaderId;

    #[OA\Property(property: 'description', description: '部门描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'sort', description: '排序权重', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态：0停用，1启用', type: 'integer')]
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
     * 获取父级部门ID（0表示顶级）
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级部门ID（0表示顶级）
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取部门名称（如：产品中心、技术研发部）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置部门名称（如：产品中心、技术研发部）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取部门编码（如：PD、RD）
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置部门编码（如：PD、RD）
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取部门负责人ID
     */
    public function getLeaderId(): int
    {
        return $this->leaderId;
    }

    /**
     * 设置部门负责人ID
     */
    public function setLeaderId(int $leaderId): void
    {
        $this->leaderId = $leaderId;
    }

    /**
     * 获取部门描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置部门描述
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
     * 获取状态：0停用，1启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0停用，1启用
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
