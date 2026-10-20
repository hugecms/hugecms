<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingFlashSaleItemEntity')]
class MarketingFlashSaleItemEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getSaleId = 'sale_id'; // 秒杀活动ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getSkuId = 'sku_id'; // SKU ID,0=单规格

    public const string getOriginalPrice = 'original_price'; // 原价快照

    public const string getPrice = 'price'; // 秒杀价

    public const string getTotalStock = 'total_stock'; // 秒杀总量

    public const string getSoldStock = 'sold_stock'; // 已售量,可购=total_stock-sold_stock

    public const string getPerLimit = 'per_limit'; // 每人限购

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=下架,1=上架

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'saleId', description: '秒杀活动ID', type: 'integer')]
    private int $saleId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID,0=单规格', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'originalPrice', description: '原价快照', type: 'string')]
    private string $originalPrice;

    #[OA\Property(property: 'price', description: '秒杀价', type: 'string')]
    private string $price;

    #[OA\Property(property: 'totalStock', description: '秒杀总量', type: 'integer')]
    private int $totalStock;

    #[OA\Property(property: 'soldStock', description: '已售量,可购=total_stock-sold_stock', type: 'integer')]
    private int $soldStock;

    #[OA\Property(property: 'perLimit', description: '每人限购', type: 'integer')]
    private int $perLimit;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=下架,1=上架', type: 'integer')]
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
     * 获取秒杀活动ID
     */
    public function getSaleId(): int
    {
        return $this->saleId;
    }

    /**
     * 设置秒杀活动ID
     */
    public function setSaleId(int $saleId): void
    {
        $this->saleId = $saleId;
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
     * 获取SKU ID,0=单规格
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置SKU ID,0=单规格
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    /**
     * 获取原价快照
     */
    public function getOriginalPrice(): string
    {
        return $this->originalPrice;
    }

    /**
     * 设置原价快照
     */
    public function setOriginalPrice(string $originalPrice): void
    {
        $this->originalPrice = $originalPrice;
    }

    /**
     * 获取秒杀价
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * 设置秒杀价
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取秒杀总量
     */
    public function getTotalStock(): int
    {
        return $this->totalStock;
    }

    /**
     * 设置秒杀总量
     */
    public function setTotalStock(int $totalStock): void
    {
        $this->totalStock = $totalStock;
    }

    /**
     * 获取已售量,可购=total_stock-sold_stock
     */
    public function getSoldStock(): int
    {
        return $this->soldStock;
    }

    /**
     * 设置已售量,可购=total_stock-sold_stock
     */
    public function setSoldStock(int $soldStock): void
    {
        $this->soldStock = $soldStock;
    }

    /**
     * 获取每人限购
     */
    public function getPerLimit(): int
    {
        return $this->perLimit;
    }

    /**
     * 设置每人限购
     */
    public function setPerLimit(int $perLimit): void
    {
        $this->perLimit = $perLimit;
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
     * 获取状态:0=下架,1=上架
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=下架,1=上架
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
