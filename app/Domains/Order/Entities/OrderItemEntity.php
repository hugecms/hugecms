<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderItemEntity')]
class OrderItemEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getSkuId = 'sku_id'; // SKU ID

    public const string getProductName = 'product_name'; // 商品名称快照

    public const string getSkuSpecs = 'sku_specs'; // 规格快照

    public const string getImage = 'image'; // 商品图快照

    public const string getPrice = 'price'; // 成交单价

    public const string getQuantity = 'quantity'; // 数量

    public const string getTotalAmount = 'total_amount'; // 小计

    public const string getCreateTime = 'create_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'productName', description: '商品名称快照', type: 'string')]
    private string $productName;

    #[OA\Property(property: 'skuSpecs', description: '规格快照', type: 'string')]
    private string $skuSpecs;

    #[OA\Property(property: 'image', description: '商品图快照', type: 'string')]
    private string $image;

    #[OA\Property(property: 'price', description: '成交单价', type: 'string')]
    private string $price;

    #[OA\Property(property: 'quantity', description: '数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'totalAmount', description: '小计', type: 'string')]
    private string $totalAmount;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
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
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
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
     * 获取SKU ID
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置SKU ID
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    /**
     * 获取商品名称快照
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * 设置商品名称快照
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    /**
     * 获取规格快照
     */
    public function getSkuSpecs(): string
    {
        return $this->skuSpecs;
    }

    /**
     * 设置规格快照
     */
    public function setSkuSpecs(string $skuSpecs): void
    {
        $this->skuSpecs = $skuSpecs;
    }

    /**
     * 获取商品图快照
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * 设置商品图快照
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * 获取成交单价
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * 设置成交单价
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    /**
     * 获取数量
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * 设置数量
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * 获取小计
     */
    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }

    /**
     * 设置小计
     */
    public function setTotalAmount(string $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
