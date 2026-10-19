<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderPaymentEntity')]
class OrderPaymentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getPaymentNo = 'payment_no'; // 支付单号

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getPayMethod = 'pay_method'; // 支付方式:wechat,alipay

    public const string getAmount = 'amount'; // 支付金额

    public const string getStatus = 'status'; // 状态:0=待支付,1=成功,2=失败

    public const string getTradeNo = 'trade_no'; // 第三方交易号

    public const string getPaidAt = 'paid_at'; // 支付成功时间

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'paymentNo', description: '支付单号', type: 'string')]
    private string $paymentNo;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'payMethod', description: '支付方式:wechat,alipay', type: 'string')]
    private string $payMethod;

    #[OA\Property(property: 'amount', description: '支付金额', type: 'string')]
    private string $amount;

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
     * 获取支付单号
     */
    public function getPaymentNo(): string
    {
        return $this->paymentNo;
    }

    /**
     * 设置支付单号
     */
    public function setPaymentNo(string $paymentNo): void
    {
        $this->paymentNo = $paymentNo;
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
     * 获取支付方式:wechat,alipay
     */
    public function getPayMethod(): string
    {
        return $this->payMethod;
    }

    /**
     * 设置支付方式:wechat,alipay
     */
    public function setPayMethod(string $payMethod): void
    {
        $this->payMethod = $payMethod;
    }

    /**
     * 获取支付金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置支付金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取状态:0=待支付,1=成功,2=失败
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待支付,1=成功,2=失败
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取第三方交易号
     */
    public function getTradeNo(): string
    {
        return $this->tradeNo;
    }

    /**
     * 设置第三方交易号
     */
    public function setTradeNo(string $tradeNo): void
    {
        $this->tradeNo = $tradeNo;
    }

    /**
     * 获取支付成功时间
     */
    public function getPaidAt(): string
    {
        return $this->paidAt;
    }

    /**
     * 设置支付成功时间
     */
    public function setPaidAt(string $paidAt): void
    {
        $this->paidAt = $paidAt;
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
