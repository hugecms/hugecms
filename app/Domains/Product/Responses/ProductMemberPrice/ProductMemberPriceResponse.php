<?php

declare(strict_types=1);

namespace App\Domains\Product\Responses\ProductMemberPrice;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ProductMemberPriceResponse')]
class ProductMemberPriceResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'productId', description: '商品ID', type: 'integer')]
    private int $productId;

    #[OA\Property(property: 'rankId', description: '会员等级ID', type: 'integer')]
    private int $rankId;

    #[OA\Property(property: 'price', description: '等级价', type: 'string')]
    private string $price;

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
     * 获取会员等级ID
     */
    public function getRankId(): int
    {
        return $this->rankId;
    }

    /**
     * 设置会员等级ID
     */
    public function setRankId(int $rankId): void
    {
        $this->rankId = $rankId;
    }

    /**
     * 获取等级价
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * 设置等级价
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
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
