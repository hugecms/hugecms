<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderEntity')]
class OrderEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderNo = 'order_no'; // 订单号

    public const string getUserId = 'user_id'; // 下单用户ID

    public const string getTotalAmount = 'total_amount'; // 商品总额

    public const string getFreightAmount = 'freight_amount'; // 运费

    public const string getDiscountAmount = 'discount_amount'; // 优惠金额

    public const string getPayAmount = 'pay_amount'; // 实付金额

    public const string getStatus = 'status'; // 状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)

    public const string getRefundStatus = 'refund_status'; // 售后状态:0=无,1=售后中,2=部分退款,3=整单退款,由order_refunds汇总回写

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

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getDeletedAt = 'deleted_at'; // 删除时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderNo', description: '订单号', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'userId', description: '下单用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'totalAmount', description: '商品总额', type: 'string')]
    private string $totalAmount;

    #[OA\Property(property: 'freightAmount', description: '运费', type: 'string')]
    private string $freightAmount;

    #[OA\Property(property: 'discountAmount', description: '优惠金额', type: 'string')]
    private string $discountAmount;

    #[OA\Property(property: 'payAmount', description: '实付金额', type: 'string')]
    private string $payAmount;

    #[OA\Property(property: 'status', description: '状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'refundStatus', description: '售后状态:0=无,1=售后中,2=部分退款,3=整单退款,由order_refunds汇总回写', type: 'integer')]
    private int $refundStatus;

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

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'deletedAt', description: '删除时间', type: 'string')]
    private string $deletedAt;

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
     * 获取订单号
     */
    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    /**
     * 设置订单号
     */
    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    /**
     * 获取下单用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置下单用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取商品总额
     */
    public function getTotalAmount(): string
    {
        return $this->totalAmount;
    }

    /**
     * 设置商品总额
     */
    public function setTotalAmount(string $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * 获取运费
     */
    public function getFreightAmount(): string
    {
        return $this->freightAmount;
    }

    /**
     * 设置运费
     */
    public function setFreightAmount(string $freightAmount): void
    {
        $this->freightAmount = $freightAmount;
    }

    /**
     * 获取优惠金额
     */
    public function getDiscountAmount(): string
    {
        return $this->discountAmount;
    }

    /**
     * 设置优惠金额
     */
    public function setDiscountAmount(string $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * 获取实付金额
     */
    public function getPayAmount(): string
    {
        return $this->payAmount;
    }

    /**
     * 设置实付金额
     */
    public function setPayAmount(string $payAmount): void
    {
        $this->payAmount = $payAmount;
    }

    /**
     * 获取状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待支付,1=已支付(待发货),2=已发货(待收货),3=已完成,4=已取消,5=已关闭(超时未支付)
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取售后状态:0=无,1=售后中,2=部分退款,3=整单退款,由order_refunds汇总回写
     */
    public function getRefundStatus(): int
    {
        return $this->refundStatus;
    }

    /**
     * 设置售后状态:0=无,1=售后中,2=部分退款,3=整单退款,由order_refunds汇总回写
     */
    public function setRefundStatus(int $refundStatus): void
    {
        $this->refundStatus = $refundStatus;
    }

    /**
     * 获取收货人
     */
    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    /**
     * 设置收货人
     */
    public function setReceiverName(string $receiverName): void
    {
        $this->receiverName = $receiverName;
    }

    /**
     * 获取收货电话
     */
    public function getReceiverPhone(): string
    {
        return $this->receiverPhone;
    }

    /**
     * 设置收货电话
     */
    public function setReceiverPhone(string $receiverPhone): void
    {
        $this->receiverPhone = $receiverPhone;
    }

    /**
     * 获取省市区
     */
    public function getReceiverRegion(): string
    {
        return $this->receiverRegion;
    }

    /**
     * 设置省市区
     */
    public function setReceiverRegion(string $receiverRegion): void
    {
        $this->receiverRegion = $receiverRegion;
    }

    /**
     * 获取详细地址
     */
    public function getReceiverAddress(): string
    {
        return $this->receiverAddress;
    }

    /**
     * 设置详细地址
     */
    public function setReceiverAddress(string $receiverAddress): void
    {
        $this->receiverAddress = $receiverAddress;
    }

    /**
     * 获取买家备注
     */
    public function getBuyerRemark(): string
    {
        return $this->buyerRemark;
    }

    /**
     * 设置买家备注
     */
    public function setBuyerRemark(string $buyerRemark): void
    {
        $this->buyerRemark = $buyerRemark;
    }

    /**
     * 获取支付时间
     */
    public function getPayTime(): string
    {
        return $this->payTime;
    }

    /**
     * 设置支付时间
     */
    public function setPayTime(string $payTime): void
    {
        $this->payTime = $payTime;
    }

    /**
     * 获取发货时间
     */
    public function getDeliverTime(): string
    {
        return $this->deliverTime;
    }

    /**
     * 设置发货时间
     */
    public function setDeliverTime(string $deliverTime): void
    {
        $this->deliverTime = $deliverTime;
    }

    /**
     * 获取完成时间
     */
    public function getFinishTime(): string
    {
        return $this->finishTime;
    }

    /**
     * 设置完成时间
     */
    public function setFinishTime(string $finishTime): void
    {
        $this->finishTime = $finishTime;
    }

    /**
     * 获取取消时间
     */
    public function getCancelTime(): string
    {
        return $this->cancelTime;
    }

    /**
     * 设置取消时间
     */
    public function setCancelTime(string $cancelTime): void
    {
        $this->cancelTime = $cancelTime;
    }

    /**
     * 获取取消原因
     */
    public function getCancelReason(): string
    {
        return $this->cancelReason;
    }

    /**
     * 设置取消原因
     */
    public function setCancelReason(string $cancelReason): void
    {
        $this->cancelReason = $cancelReason;
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

    /**
     * 获取删除时间
     */
    public function getDeletedAt(): string
    {
        return $this->deletedAt;
    }

    /**
     * 设置删除时间
     */
    public function setDeletedAt(string $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }
}
