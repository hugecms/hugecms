<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingUserCoupon;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingUserCouponResponse')]
class MarketingUserCouponResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'couponId', description: '优惠券类型ID', type: 'integer')]
    private int $couponId;

    #[OA\Property(property: 'userId', description: '持有用户ID,发放即创建归属', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'orderId', description: '核销订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'status', description: '状态:0=未使用,1=已使用,2=已过期', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'usedAt', description: '核销时间', type: 'string')]
    private string $usedAt;

    #[OA\Property(property: 'expireAt', description: '过期时间,空=按券类型有效期', type: 'string')]
    private string $expireAt;

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
     * 获取优惠券类型ID
     */
    public function getCouponId(): int
    {
        return $this->couponId;
    }

    /**
     * 设置优惠券类型ID
     */
    public function setCouponId(int $couponId): void
    {
        $this->couponId = $couponId;
    }

    /**
     * 获取持有用户ID,发放即创建归属
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置持有用户ID,发放即创建归属
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取核销订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置核销订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取状态:0=未使用,1=已使用,2=已过期
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=未使用,1=已使用,2=已过期
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取核销时间
     */
    public function getUsedAt(): string
    {
        return $this->usedAt;
    }

    /**
     * 设置核销时间
     */
    public function setUsedAt(string $usedAt): void
    {
        $this->usedAt = $usedAt;
    }

    /**
     * 获取过期时间,空=按券类型有效期
     */
    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    /**
     * 设置过期时间,空=按券类型有效期
     */
    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
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
