<?php

declare(strict_types=1);

namespace app\domain\order\response\orderPayment;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderPaymentResponse')]
class OrderPaymentResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'paymentNo', description: '支付单号', type: 'string')]
    private string $paymentNo;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'payMethod', description: '支付方式:wechat,alipay', type: 'string')]
    private string $payMethod;

    #[OA\Property(property: 'amount', description: '支付金额', type: 'float')]
    private float $amount;

    #[OA\Property(property: 'status', description: '状态:0=待支付,1=成功,2=失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'tradeNo', description: '第三方交易号', type: 'string')]
    private string $tradeNo;

    #[OA\Property(property: 'paidAt', description: '支付成功时间', type: 'string')]
    private string $paidAt;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
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
