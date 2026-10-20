<?php

declare(strict_types=1);

namespace App\Domains\Order\Responses\OrderCart;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderCartResponse')]
class OrderCartResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'skuId', description: 'SKU ID,0=单规格', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'productName', description: '商品名称快照', type: 'string')]
    private string $productName;

    #[OA\Property(property: 'image', description: '商品图快照', type: 'string')]
    private string $image;

    #[OA\Property(property: 'price', description: '加购时价格快照', type: 'string')]
    private string $price;

    #[OA\Property(property: 'quantity', description: '数量', type: 'integer')]
    private int $quantity;

    #[OA\Property(property: 'checked', description: '结算勾选:0=否,1=是', type: 'integer')]
    private int $checked;

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
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
     * 获取加购时价格快照
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * 设置加购时价格快照
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
     * 获取结算勾选:0=否,1=是
     */
    public function getChecked(): int
    {
        return $this->checked;
    }

    /**
     * 设置结算勾选:0=否,1=是
     */
    public function setChecked(int $checked): void
    {
        $this->checked = $checked;
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
