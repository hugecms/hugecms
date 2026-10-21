<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingPromotionLadderEntity')]
class MarketingPromotionLadderEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getPromotionId = 'promotion_id'; // 促销活动ID

    public const string getMinAmount = 'min_amount'; // 门槛金额,0=无门槛

    public const string getReduceAmount = 'reduce_amount'; // 满减金额,full_reduce类型必填

    public const string getDiscountRate = 'discount_rate'; // 折扣率,如8.5表示85折,discount类型必填

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'promotionId', description: '促销活动ID', type: 'integer')]
    private int $promotionId;

    #[OA\Property(property: 'minAmount', description: '门槛金额,0=无门槛', type: 'string')]
    private string $minAmount;

    #[OA\Property(property: 'reduceAmount', description: '满减金额,full_reduce类型必填', type: 'string')]
    private string $reduceAmount;

    #[OA\Property(property: 'discountRate', description: '折扣率,如8.5表示85折,discount类型必填', type: 'string')]
    private string $discountRate;

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
     * 获取促销活动ID
     */
    public function getPromotionId(): int
    {
        return $this->promotionId;
    }

    /**
     * 设置促销活动ID
     */
    public function setPromotionId(int $promotionId): void
    {
        $this->promotionId = $promotionId;
    }

    /**
     * 获取门槛金额,0=无门槛
     */
    public function getMinAmount(): string
    {
        return $this->minAmount;
    }

    /**
     * 设置门槛金额,0=无门槛
     */
    public function setMinAmount(string $minAmount): void
    {
        $this->minAmount = $minAmount;
    }

    /**
     * 获取满减金额,full_reduce类型必填
     */
    public function getReduceAmount(): string
    {
        return $this->reduceAmount;
    }

    /**
     * 设置满减金额,full_reduce类型必填
     */
    public function setReduceAmount(string $reduceAmount): void
    {
        $this->reduceAmount = $reduceAmount;
    }

    /**
     * 获取折扣率,如8.5表示85折,discount类型必填
     */
    public function getDiscountRate(): string
    {
        return $this->discountRate;
    }

    /**
     * 设置折扣率,如8.5表示85折,discount类型必填
     */
    public function setDiscountRate(string $discountRate): void
    {
        $this->discountRate = $discountRate;
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
