<?php

declare(strict_types=1);

namespace App\Domains\Product\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductAttributeValueEntity')]
class ProductAttributeValueEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getAttributeId = 'attribute_id'; // 属性ID

    public const string getValue = 'value'; // 属性值

    public const string getSkuId = 'sku_id'; // 关联SKU ID,规格属性时对应

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'attributeId', description: '属性ID', type: 'integer')]
    private int $attributeId;

    #[OA\Property(property: 'value', description: '属性值', type: 'string')]
    private string $value;

    #[OA\Property(property: 'skuId', description: '关联SKU ID,规格属性时对应', type: 'integer')]
    private int $skuId;

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
     * 获取商品ID
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * 设置商品ID
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * 获取属性ID
     */
    public function getAttributeId(): int
    {
        return $this->attributeId;
    }

    /**
     * 设置属性ID
     */
    public function setAttributeId(int $attributeId): void
    {
        $this->attributeId = $attributeId;
    }

    /**
     * 获取属性值
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 设置属性值
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * 获取关联SKU ID,规格属性时对应
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置关联SKU ID,规格属性时对应
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
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
