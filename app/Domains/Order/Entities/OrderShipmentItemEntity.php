<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderShipmentItemEntity')]
class OrderShipmentItemEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getShipmentId = 'shipment_id'; // 发货单ID

    public const string getOrderItemId = 'order_item_id'; // 订单明细ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getSkuId = 'sku_id'; // SKU ID,0=单规格

    public const string getQuantity = 'quantity'; // 发货数量

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shipmentId', description: '发货单ID', type: 'integer')]
    private int $shipmentId;

    #[OA\Property(property: 'orderItemId', description: '订单明细ID', type: 'integer')]
    private int $orderItemId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID,0=单规格', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'quantity', description: '发货数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
    private string $createTime;

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
     * 获取发货单ID
     */
    public function getShipmentId(): int
    {
        return $this->shipmentId;
    }

    /**
     * 设置发货单ID
     */
    public function setShipmentId(int $shipmentId): void
    {
        $this->shipmentId = $shipmentId;
    }

    /**
     * 获取订单明细ID
     */
    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    /**
     * 设置订单明细ID
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
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
     * 获取发货数量
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * 设置发货数量
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
