<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingCoupon;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingCouponResponse')]
class MarketingCouponResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '券名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券', type: 'string')]
    private string $type;

    #[OA\Property(property: 'minAmount', description: '使用门槛,0=无门槛', type: 'string')]
    private string $minAmount;

    #[OA\Property(property: 'reduceAmount', description: '满减金额,满减券必填', type: 'string')]
    private string $reduceAmount;

    #[OA\Property(property: 'discountRate', description: '折扣率,如8.5表示85折,折扣券必填', type: 'string')]
    private string $discountRate;

    #[OA\Property(property: 'receiveType', description: '发放方式:manual=后台发放,order=下单赠送,register=注册赠送', type: 'string')]
    private string $receiveType;

    #[OA\Property(property: 'total', description: '发放总量,0=不限;领券事务条件更新 received_count &lt; total 防超发', type: 'integer')]
    private int $total;

    #[OA\Property(property: 'receivedCount', description: '已领取数量,领券事务内条件更新 received_count &lt; total 防超发', type: 'integer')]
    private int $receivedCount;

    #[OA\Property(property: 'perLimit', description: '每人限领', type: 'integer')]
    private int $perLimit;

    #[OA\Property(property: 'validType', description: '有效期方式:1=固定区间(start_time/end_time),2=领取后N天', type: 'integer')]
    private int $validType;

    #[OA\Property(property: 'validDays', description: '领取后有效天数,valid_type=2 时必填', type: 'integer')]
    private int $validDays;

    #[OA\Property(property: 'startTime', description: '有效期开始,空=领取即生效', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '有效期结束', type: 'string')]
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
     * 获取券名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置券名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取使用门槛,0=无门槛
     */
    public function getMinAmount(): string
    {
        return $this->minAmount;
    }

    /**
     * 设置使用门槛,0=无门槛
     */
    public function setMinAmount(string $minAmount): void
    {
        $this->minAmount = $minAmount;
    }

    /**
     * 获取满减金额,满减券必填
     */
    public function getReduceAmount(): string
    {
        return $this->reduceAmount;
    }

    /**
     * 设置满减金额,满减券必填
     */
    public function setReduceAmount(string $reduceAmount): void
    {
        $this->reduceAmount = $reduceAmount;
    }

    /**
     * 获取折扣率,如8.5表示85折,折扣券必填
     */
    public function getDiscountRate(): string
    {
        return $this->discountRate;
    }

    /**
     * 设置折扣率,如8.5表示85折,折扣券必填
     */
    public function setDiscountRate(string $discountRate): void
    {
        $this->discountRate = $discountRate;
    }

    /**
     * 获取发放方式:manual=后台发放,order=下单赠送,register=注册赠送
     */
    public function getReceiveType(): string
    {
        return $this->receiveType;
    }

    /**
     * 设置发放方式:manual=后台发放,order=下单赠送,register=注册赠送
     */
    public function setReceiveType(string $receiveType): void
    {
        $this->receiveType = $receiveType;
    }

    /**
     * 获取发放总量,0=不限;领券事务条件更新 received_count &lt; total 防超发
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * 设置发放总量,0=不限;领券事务条件更新 received_count &lt; total 防超发
     */
    public function setTotal(int $total): void
    {
        $this->total = $total;
    }

    /**
     * 获取已领取数量,领券事务内条件更新 received_count &lt; total 防超发
     */
    public function getReceivedCount(): int
    {
        return $this->receivedCount;
    }

    /**
     * 设置已领取数量,领券事务内条件更新 received_count &lt; total 防超发
     */
    public function setReceivedCount(int $receivedCount): void
    {
        $this->receivedCount = $receivedCount;
    }

    /**
     * 获取每人限领
     */
    public function getPerLimit(): int
    {
        return $this->perLimit;
    }

    /**
     * 设置每人限领
     */
    public function setPerLimit(int $perLimit): void
    {
        $this->perLimit = $perLimit;
    }

    /**
     * 获取有效期方式:1=固定区间(start_time/end_time),2=领取后N天
     */
    public function getValidType(): int
    {
        return $this->validType;
    }

    /**
     * 设置有效期方式:1=固定区间(start_time/end_time),2=领取后N天
     */
    public function setValidType(int $validType): void
    {
        $this->validType = $validType;
    }

    /**
     * 获取领取后有效天数,valid_type=2 时必填
     */
    public function getValidDays(): int
    {
        return $this->validDays;
    }

    /**
     * 设置领取后有效天数,valid_type=2 时必填
     */
    public function setValidDays(int $validDays): void
    {
        $this->validDays = $validDays;
    }

    /**
     * 获取有效期开始,空=领取即生效
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置有效期开始,空=领取即生效
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取有效期结束
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置有效期结束
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
