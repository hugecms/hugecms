<?php

declare(strict_types=1);

namespace app\domain\order\entity;

use Juling\Foundation\Support\ArrayHelper;

class OrderPaymentEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getPaymentNo = 'payment_no'; // 支付单号

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getPayMethod = 'pay_method'; // 支付方式:wechat,alipay

    public const string getAmount = 'amount'; // 支付金额

    public const string getStatus = 'status'; // 状态:0=待支付,1=成功,2=失败

    public const string getTradeNo = 'trade_no'; // 第三方交易号

    public const string getPaidAt = 'paid_at'; // 支付成功时间

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $id;

    private string $paymentNo;

    private int $orderId;

    private string $payMethod;

    private float $amount;

    private int $status;

    private string $tradeNo;

    private string $paidAt;

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

    public function getPaymentNo(): string
    {
        return $this->paymentNo;
    }

    public function setPaymentNo(string $paymentNo): void
    {
        $this->paymentNo = $paymentNo;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getPayMethod(): string
    {
        return $this->payMethod;
    }

    public function setPayMethod(string $payMethod): void
    {
        $this->payMethod = $payMethod;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getTradeNo(): string
    {
        return $this->tradeNo;
    }

    public function setTradeNo(string $tradeNo): void
    {
        $this->tradeNo = $tradeNo;
    }

    public function getPaidAt(): string
    {
        return $this->paidAt;
    }

    public function setPaidAt(string $paidAt): void
    {
        $this->paidAt = $paidAt;
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
