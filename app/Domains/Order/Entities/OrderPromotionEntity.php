<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderPromotionEntity')]
class OrderPromotionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getType = 'type'; // 优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动

    public const string getRelatedId = 'related_id'; // 关联ID:coupon=用户券ID,promotion=活动ID,points=空

    public const string getName = 'name'; // 优惠名称快照,如:满100减20

    public const string getOrderItemId = 'order_item_id'; // 订单明细ID,空=全单优惠

    public const string getAmount = 'amount'; // 抵扣金额

    public const string getPoints = 'points'; // 消耗积分,积分抵扣时记录,退款按比例返还

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'type', description: '优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动', type: 'string')]
    private string $type;

    #[OA\Property(property: 'relatedId', description: '关联ID:coupon=用户券ID,promotion=活动ID,points=空', type: 'integer')]
    private int $relatedId;

    #[OA\Property(property: 'name', description: '优惠名称快照,如:满100减20', type: 'string')]
    private string $name;

    #[OA\Property(property: 'orderItemId', description: '订单明细ID,空=全单优惠', type: 'integer')]
    private int $orderItemId;

    #[OA\Property(property: 'amount', description: '抵扣金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'points', description: '消耗积分,积分抵扣时记录,退款按比例返还', type: 'integer')]
    private int $points;

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
     * 获取优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取关联ID:coupon=用户券ID,promotion=活动ID,points=空
     */
    public function getRelatedId(): int
    {
        return $this->relatedId;
    }

    /**
     * 设置关联ID:coupon=用户券ID,promotion=活动ID,points=空
     */
    public function setRelatedId(int $relatedId): void
    {
        $this->relatedId = $relatedId;
    }

    /**
     * 获取优惠名称快照,如:满100减20
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置优惠名称快照,如:满100减20
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取订单明细ID,空=全单优惠
     */
    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    /**
     * 设置订单明细ID,空=全单优惠
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
    }

    /**
     * 获取抵扣金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置抵扣金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取消耗积分,积分抵扣时记录,退款按比例返还
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * 设置消耗积分,积分抵扣时记录,退款按比例返还
     */
    public function setPoints(int $points): void
    {
        $this->points = $points;
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
