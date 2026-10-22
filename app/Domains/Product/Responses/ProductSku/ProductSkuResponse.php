<?php

declare(strict_types=1);

namespace App\Domains\Product\Responses\ProductSku;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductSkuResponse')]
class ProductSkuResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'specs', description: '规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}', type: 'string')]
    private string $specs;

    #[OA\Property(property: 'skuNo', description: 'SKU编号', type: 'string')]
    private string $skuNo;

    #[OA\Property(property: 'barcode', description: '条码,仓储/扫码出入库', type: 'string')]
    private string $barcode;

    #[OA\Property(property: 'image', description: 'SKU图片', type: 'string')]
    private string $image;

    #[OA\Property(property: 'price', description: '售价', type: 'string')]
    private string $price;

    #[OA\Property(property: 'linePrice', description: '划线价(市场价展示)', type: 'string')]
    private string $linePrice;

    #[OA\Property(property: 'costPrice', description: '成本价,毛利核算', type: 'string')]
    private string $costPrice;

    #[OA\Property(property: 'weight', description: '重量(kg),运费计算', type: 'string')]
    private string $weight;

    #[OA\Property(property: 'stock', description: '库存', type: 'integer')]
    private int $stock;

    #[OA\Property(property: 'warnStock', description: '库存预警线,库存&lt;=该值触发预警,0=不预警', type: 'integer')]
    private int $warnStock;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

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
     * 获取规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}
     */
    public function getSpecs(): string
    {
        return $this->specs;
    }

    /**
     * 设置规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}
     */
    public function setSpecs(string $specs): void
    {
        $this->specs = $specs;
    }

    /**
     * 获取SKU编号
     */
    public function getSkuNo(): string
    {
        return $this->skuNo;
    }

    /**
     * 设置SKU编号
     */
    public function setSkuNo(string $skuNo): void
    {
        $this->skuNo = $skuNo;
    }

    /**
     * 获取条码,仓储/扫码出入库
     */
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * 设置条码,仓储/扫码出入库
     */
    public function setBarcode(string $barcode): void
    {
        $this->barcode = $barcode;
    }

    /**
     * 获取SKU图片
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * 设置SKU图片
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * 获取售价
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * 设置售价
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取划线价(市场价展示)
     */
    public function getLinePrice(): string
    {
        return $this->linePrice;
    }

    /**
     * 设置划线价(市场价展示)
     */
    public function setLinePrice(string $linePrice): void
    {
        $this->linePrice = $linePrice;
    }

    /**
     * 获取成本价,毛利核算
     */
    public function getCostPrice(): string
    {
        return $this->costPrice;
    }

    /**
     * 设置成本价,毛利核算
     */
    public function setCostPrice(string $costPrice): void
    {
        $this->costPrice = $costPrice;
    }

    /**
     * 获取重量(kg),运费计算
     */
    public function getWeight(): string
    {
        return $this->weight;
    }

    /**
     * 设置重量(kg),运费计算
     */
    public function setWeight(string $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * 获取库存
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * 设置库存
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * 获取库存预警线,库存&lt;=该值触发预警,0=不预警
     */
    public function getWarnStock(): int
    {
        return $this->warnStock;
    }

    /**
     * 设置库存预警线,库存&lt;=该值触发预警,0=不预警
     */
    public function setWarnStock(int $warnStock): void
    {
        $this->warnStock = $warnStock;
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

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
