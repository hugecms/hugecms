<?php

declare(strict_types=1);

namespace app\domain\order\response\order;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderResponse')]
class OrderResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderNo', description: '订单号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'userId', description: '下单用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'totalAmount', description: '商品总额', type: 'float')]
    private float $totalAmount;

    #[OA\Property(property: 'freightAmount', description: '运费', type: 'float')]
    private float $freightAmount;

    #[OA\Property(property: 'discountAmount', description: '优惠金额', type: 'float')]
    private float $discountAmount;

    #[OA\Property(property: 'payAmount', description: '实付金额', type: 'float')]
    private float $payAmount;

    #[OA\Property(property: 'status', description: '状态:0=待支付,1=已支付,2=已发货,3=已完成,4=已取消', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'receiverName', description: '收货人', type: 'string')]
    private string $receiverName;

    #[OA\Property(property: 'receiverPhone', description: '收货电话', type: 'string')]
    private string $receiverPhone;

    #[OA\Property(property: 'receiverRegion', description: '省市区', type: 'string')]
    private string $receiverRegion;

    #[OA\Property(property: 'receiverAddress', description: '详细地址', type: 'string')]
    private string $receiverAddress;

    #[OA\Property(property: 'buyerRemark', description: '买家备注', type: 'string')]
    private string $buyerRemark;

    #[OA\Property(property: 'payTime', description: '支付时间', type: 'string')]
    private string $payTime;

    #[OA\Property(property: 'deliverTime', description: '发货时间', type: 'string')]
    private string $deliverTime;

    #[OA\Property(property: 'finishTime', description: '完成时间', type: 'string')]
    private string $finishTime;

    #[OA\Property(property: 'cancelTime', description: '取消时间', type: 'string')]
    private string $cancelTime;

    #[OA\Property(property: 'cancelReason', description: '取消原因', type: 'string')]
    private string $cancelReason;

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
