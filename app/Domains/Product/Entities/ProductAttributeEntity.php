<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductAttributeEntity')]
class ProductAttributeEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getTypeId = 'type_id'; // 所属商品类型ID

    public const string getName = 'name'; // 属性名,如:颜色

    public const string getInputType = 'input_type'; // 录入方式:1=手工输入,2=列表选择

    public const string getValues = 'values'; // 可选值列表(逗号分隔),列表选择时用

    public const string getIsSpec = 'is_spec'; // 是否规格属性:0=否,1=是(参与SKU组合)

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'typeId', description: '所属商品类型ID', type: 'integer')]
    private int $typeId;

    #[OA\Property(property: 'name', description: '属性名,如:颜色', type: 'string')]
    private string $name;

    #[OA\Property(property: 'inputType', description: '录入方式:1=手工输入,2=列表选择', type: 'integer')]
    private int $inputType;

    #[OA\Property(property: 'values', description: '可选值列表(逗号分隔),列表选择时用', type: 'string')]
    private string $values;

    #[OA\Property(property: 'isSpec', description: '是否规格属性:0=否,1=是(参与SKU组合)', type: 'integer')]
    private int $isSpec;

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
     * 获取所属商品类型ID
     */
    public function getTypeId(): int
    {
        return $this->typeId;
    }

    /**
     * 设置所属商品类型ID
     */
    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    /**
     * 获取属性名,如:颜色
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置属性名,如:颜色
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取录入方式:1=手工输入,2=列表选择
     */
    public function getInputType(): int
    {
        return $this->inputType;
    }

    /**
     * 设置录入方式:1=手工输入,2=列表选择
     */
    public function setInputType(int $inputType): void
    {
        $this->inputType = $inputType;
    }

    /**
     * 获取可选值列表(逗号分隔),列表选择时用
     */
    public function getValues(): string
    {
        return $this->values;
    }

    /**
     * 设置可选值列表(逗号分隔),列表选择时用
     */
    public function setValues(string $values): void
    {
        $this->values = $values;
    }

    /**
     * 获取是否规格属性:0=否,1=是(参与SKU组合)
     */
    public function getIsSpec(): int
    {
        return $this->isSpec;
    }

    /**
     * 设置是否规格属性:0=否,1=是(参与SKU组合)
     */
    public function setIsSpec(int $isSpec): void
    {
        $this->isSpec = $isSpec;
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
