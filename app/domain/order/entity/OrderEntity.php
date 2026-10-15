<?php

declare(strict_types=1);

namespace app\domain\order\entity;

use Juling\Foundation\Support\ArrayHelper;

class OrderEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getOrderNo = 'order_no'; // 订单号

    public const string getUserId = 'user_id'; // 下单用户ID

    public const string getTotalAmount = 'total_amount'; // 商品总额

    public const string getFreightAmount = 'freight_amount'; // 运费

    public const string getDiscountAmount = 'discount_amount'; // 优惠金额

    public const string getPayAmount = 'pay_amount'; // 实付金额

    public const string getStatus = 'status'; // 状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消

    public const string getReceiverName = 'receiver_name'; // 收货人

    public const string getReceiverPhone = 'receiver_phone'; // 收货电话

    public const string getReceiverRegion = 'receiver_region'; // 省市区

    public const string getReceiverAddress = 'receiver_address'; // 详细地址

    public const string getBuyerRemark = 'buyer_remark'; // 买家备注

    public const string getPayTime = 'pay_time'; // 支付时间

    public const string getDeliverTime = 'deliver_time'; // 发货时间

    public const string getFinishTime = 'finish_time'; // 完成时间

    public const string getCancelTime = 'cancel_time'; // 取消时间

    public const string getCancelReason = 'cancel_reason'; // 取消原因

    public const string getCreateTime = 'create_time'; //

    public const string getUpdateTime = 'update_time'; //

    private int $id;

    private string $orderNo;

    private int $userId;

    private float $totalAmount;

    private float $freightAmount;

    private float $discountAmount;

    private float $payAmount;

    private int $status;

    private string $receiverName;

    private string $receiverPhone;

    private string $receiverRegion;

    private string $receiverAddress;

    private string $buyerRemark;

    private string $payTime;

    private string $deliverTime;

    private string $finishTime;

    private string $cancelTime;

    private string $cancelReason;

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

    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getFreightAmount(): float
    {
        return $this->freightAmount;
    }

    public function setFreightAmount(float $freightAmount): void
    {
        $this->freightAmount = $freightAmount;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(float $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    public function getPayAmount(): float
    {
        return $this->payAmount;
    }

    public function setPayAmount(float $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    public function setReceiverName(string $receiverName): void
    {
        $this->receiverName = $receiverName;
    }

    public function getReceiverPhone(): string
    {
        return $this->receiverPhone;
    }

    public function setReceiverPhone(string $receiverPhone): void
    {
        $this->receiverPhone = $receiverPhone;
    }

    public function getReceiverRegion(): string
    {
        return $this->receiverRegion;
    }

    public function setReceiverRegion(string $receiverRegion): void
    {
        $this->receiverRegion = $receiverRegion;
    }

    public function getReceiverAddress(): string
    {
        return $this->receiverAddress;
    }

    public function setReceiverAddress(string $receiverAddress): void
    {
        $this->receiverAddress = $receiverAddress;
    }

    public function getBuyerRemark(): string
    {
        return $this->buyerRemark;
    }

    public function setBuyerRemark(string $buyerRemark): void
    {
        $this->buyerRemark = $buyerRemark;
    }

    public function getPayTime(): string
    {
        return $this->payTime;
    }

    public function setPayTime(string $payTime): void
    {
        $this->payTime = $payTime;
    }

    public function getDeliverTime(): string
    {
        return $this->deliverTime;
    }

    public function setDeliverTime(string $deliverTime): void
    {
        $this->deliverTime = $deliverTime;
    }

    public function getFinishTime(): string
    {
        return $this->finishTime;
    }

    public function setFinishTime(string $finishTime): void
    {
        $this->finishTime = $finishTime;
    }

    public function getCancelTime(): string
    {
        return $this->cancelTime;
    }

    public function setCancelTime(string $cancelTime): void
    {
        $this->cancelTime = $cancelTime;
    }

    public function getCancelReason(): string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(string $cancelReason): void
    {
        $this->cancelReason = $cancelReason;
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
