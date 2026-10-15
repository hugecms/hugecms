<?php

declare(strict_types=1);

namespace app\domain\order\entity;

use Juling\Foundation\Support\ArrayHelper;

class OrderItemEntity
{
    use ArrayHelper;

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

    public const string getCreateTime = 'create_time'; //

    private int $id;

    private int $orderId;

    private int $productId;

    private int $skuId;

    private string $productName;

    private string $skuSpecs;

    private string $image;

    private float $price;

    private int $quantity;

    private float $totalAmount;

    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getSkuId(): int
    {
        return $this->skuId;
    }

    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    public function getSkuSpecs(): string
    {
        return $this->skuSpecs;
    }

    public function setSkuSpecs(string $skuSpecs): void
    {
        $this->skuSpecs = $skuSpecs;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
