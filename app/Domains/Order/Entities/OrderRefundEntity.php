<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderRefundEntity')]
class OrderRefundEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getRefundNo = 'refund_no'; // 退款单号

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getPaymentId = 'payment_id'; // 支付单ID

    public const string getAmount = 'amount'; // 退款金额

    public const string getReason = 'reason'; // 退款原因

    public const string getStatus = 'status'; // 状态:0=待审核,1=退款中,2=已退款,3=已拒绝

    public const string getRefundedAt = 'refunded_at'; // 退款完成时间

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'refundNo', description: '退款单号', type: 'string')]
    private string $refundNo;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'paymentId', description: '支付单ID', type: 'integer')]
    private int $paymentId;

    #[OA\Property(property: 'amount', description: '退款金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'reason', description: '退款原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'status', description: '状态:0=待审核,1=退款中,2=已退款,3=已拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'refundedAt', description: '退款完成时间', type: 'string')]
    private string $refundedAt;

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
     * 获取退款单号
     */
    public function getRefundNo(): string
    {
        return $this->refundNo;
    }

    /**
     * 设置退款单号
     */
    public function setRefundNo(string $refundNo): void
    {
        $this->refundNo = $refundNo;
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
     * 获取支付单ID
     */
    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    /**
     * 设置支付单ID
     */
    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    /**
     * 获取退款金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置退款金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取退款原因
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * 设置退款原因
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * 获取状态:0=待审核,1=退款中,2=已退款,3=已拒绝
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待审核,1=退款中,2=已退款,3=已拒绝
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取退款完成时间
     */
    public function getRefundedAt(): string
    {
        return $this->refundedAt;
    }

    /**
     * 设置退款完成时间
     */
    public function setRefundedAt(string $refundedAt): void
    {
        $this->refundedAt = $refundedAt;
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
