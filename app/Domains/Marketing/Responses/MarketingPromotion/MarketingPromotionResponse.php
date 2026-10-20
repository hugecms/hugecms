<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingPromotion;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingPromotionResponse')]
class MarketingPromotionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '活动名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '类型:full_reduce=满减,discount=折扣', type: 'string')]
    private string $type;

    #[OA\Property(property: 'rangeType', description: '适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品', type: 'string')]
    private string $rangeType;

    #[OA\Property(property: 'rangeValues', description: '范围内ID列表(JSON),all 时为空', type: 'string')]
    private string $rangeValues;

    #[OA\Property(property: 'minAmount', description: '门槛金额,0=无门槛', type: 'string')]
    private string $minAmount;

    #[OA\Property(property: 'reduceAmount', description: '满减金额,满减活动必填', type: 'string')]
    private string $reduceAmount;

    #[OA\Property(property: 'discountRate', description: '折扣率,如8.5表示85折,折扣活动必填', type: 'string')]
    private string $discountRate;

    #[OA\Property(property: 'startTime', description: '活动开始时间', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '活动结束时间', type: 'string')]
    private string $endTime;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
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
     * 获取活动名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置活动名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取类型:full_reduce=满减,discount=折扣
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:full_reduce=满减,discount=折扣
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
     */
    public function getRangeType(): string
    {
        return $this->rangeType;
    }

    /**
     * 设置适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
     */
    public function setRangeType(string $rangeType): void
    {
        $this->rangeType = $rangeType;
    }

    /**
     * 获取范围内ID列表(JSON),all 时为空
     */
    public function getRangeValues(): string
    {
        return $this->rangeValues;
    }

    /**
     * 设置范围内ID列表(JSON),all 时为空
     */
    public function setRangeValues(string $rangeValues): void
    {
        $this->rangeValues = $rangeValues;
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
     * 获取满减金额,满减活动必填
     */
    public function getReduceAmount(): string
    {
        return $this->reduceAmount;
    }

    /**
     * 设置满减金额,满减活动必填
     */
    public function setReduceAmount(string $reduceAmount): void
    {
        $this->reduceAmount = $reduceAmount;
    }

    /**
     * 获取折扣率,如8.5表示85折,折扣活动必填
     */
    public function getDiscountRate(): string
    {
        return $this->discountRate;
    }

    /**
     * 设置折扣率,如8.5表示85折,折扣活动必填
     */
    public function setDiscountRate(string $discountRate): void
    {
        $this->discountRate = $discountRate;
    }

    /**
     * 获取活动开始时间
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置活动开始时间
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取活动结束时间
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置活动结束时间
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
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
