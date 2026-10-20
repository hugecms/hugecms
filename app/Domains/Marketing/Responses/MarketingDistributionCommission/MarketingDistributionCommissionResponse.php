<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingDistributionCommission;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingDistributionCommissionResponse')]
class MarketingDistributionCommissionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'distributorId', description: '分销员用户ID', type: 'integer')]
    private int $distributorId;

    #[OA\Property(property: 'userId', description: '购买用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'orderItemId', description: '订单明细ID,空=整单计佣', type: 'integer')]
    private int $orderItemId;

    #[OA\Property(property: 'level', description: '层级:1=一级,2=二级', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'orderAmount', description: '计佣基数(商品实付)', type: 'string')]
    private string $orderAmount;

    #[OA\Property(property: 'rate', description: '佣金比例(%),如5.00表示5%', type: 'string')]
    private string $rate;

    #[OA\Property(property: 'amount', description: '佣金金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'status', description: '状态:0=待结算,1=已结算,2=已失效(退款)', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'settledAt', description: '结算时间', type: 'string')]
    private string $settledAt;

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
     * 获取分销员用户ID
     */
    public function getDistributorId(): int
    {
        return $this->distributorId;
    }

    /**
     * 设置分销员用户ID
     */
    public function setDistributorId(int $distributorId): void
    {
        $this->distributorId = $distributorId;
    }

    /**
     * 获取购买用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置购买用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
     * 获取订单明细ID,空=整单计佣
     */
    public function getOrderItemId(): int
    {
        return $this->orderItemId;
    }

    /**
     * 设置订单明细ID,空=整单计佣
     */
    public function setOrderItemId(int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
    }

    /**
     * 获取层级:1=一级,2=二级
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置层级:1=一级,2=二级
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取计佣基数(商品实付)
     */
    public function getOrderAmount(): string
    {
        return $this->orderAmount;
    }

    /**
     * 设置计佣基数(商品实付)
     */
    public function setOrderAmount(string $orderAmount): void
    {
        $this->orderAmount = $orderAmount;
    }

    /**
     * 获取佣金比例(%),如5.00表示5%
     */
    public function getRate(): string
    {
        return $this->rate;
    }

    /**
     * 设置佣金比例(%),如5.00表示5%
     */
    public function setRate(string $rate): void
    {
        $this->rate = $rate;
    }

    /**
     * 获取佣金金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置佣金金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取状态:0=待结算,1=已结算,2=已失效(退款)
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待结算,1=已结算,2=已失效(退款)
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取结算时间
     */
    public function getSettledAt(): string
    {
        return $this->settledAt;
    }

    /**
     * 设置结算时间
     */
    public function setSettledAt(string $settledAt): void
    {
        $this->settledAt = $settledAt;
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
