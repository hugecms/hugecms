<?php

declare(strict_types=1);

namespace App\Domains\System\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemDictItemEntity')]
class SystemDictItemEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getDictId = 'dict_id'; // 字典类型ID

    public const string getValue = 'value'; // 字典值

    public const string getLabel = 'label'; // 字典标签

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'dictId', description: '字典类型ID', type: 'integer')]
    private int $dictId;

    #[OA\Property(property: 'value', description: '字典值', type: 'string')]
    private string $value;

    #[OA\Property(property: 'label', description: '字典标签', type: 'string')]
    private string $label;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer')]
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
     * 获取字典类型ID
     */
    public function getDictId(): int
    {
        return $this->dictId;
    }

    /**
     * 设置字典类型ID
     */
    public function setDictId(int $dictId): void
    {
        $this->dictId = $dictId;
    }

    /**
     * 获取字典值
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 设置字典值
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * 获取字典标签
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * 设置字典标签
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
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
     * 获取状态:0=禁用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=禁用,1=启用
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
