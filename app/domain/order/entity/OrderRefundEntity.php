<?php

declare(strict_types=1);

namespace app\domain\order\entity;

use Juling\Foundation\Support\ArrayHelper;

class OrderRefundEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getRefundNo = 'refund_no'; // 退款单号

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getPaymentId = 'payment_id'; // 支付单ID

    public const string getAmount = 'amount'; // 退款金额

    public const string getReason = 'reason'; // 退款原因

    public const string getStatus = 'status'; // 状态:0=待审核,1=退款中,2=已退款,3=已拒绝

    public const string getRefundedAt = 'refunded_at'; // 退款完成时间

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $id;

    private string $refundNo;

    private int $orderId;

    private int $paymentId;

    private float $amount;

    private string $reason;

    private int $status;

    private string $refundedAt;

    private string $createTime;

    private string $updateTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRefundNo(): string
    {
        return $this->refundNo;
    }

    public function setRefundNo(string $refundNo): void
    {
        $this->refundNo = $refundNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getRefundedAt(): string
    {
        return $this->refundedAt;
    }

    public function setRefundedAt(string $refundedAt): void
    {
        $this->refundedAt = $refundedAt;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
