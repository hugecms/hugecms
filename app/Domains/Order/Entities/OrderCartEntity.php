<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderCartEntity')]
class OrderCartEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getProductId = 'product_id'; // 商品ID

    public const string getSkuId = 'sku_id'; // SKU ID,0=单规格

    public const string getProductName = 'product_name'; // 商品名称快照

    public const string getImage = 'image'; // 商品图快照

    public const string getPrice = 'price'; // 加购时价格快照

    public const string getQuantity = 'quantity'; // 数量

    public const string getChecked = 'checked'; // 结算勾选:0=否,1=是

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

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

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

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

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
