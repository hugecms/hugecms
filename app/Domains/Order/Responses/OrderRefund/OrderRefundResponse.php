<?php

declare(strict_types=1);

namespace App\Domains\Order\Responses\OrderRefund;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderRefundResponse')]
class OrderRefundResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'refundNo', description: '退款单号', type: 'string')]
    private string $refundNo;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'paymentId', description: '关联支付单ID(payments 表)', type: 'integer')]
    private int $paymentId;

    #[OA\Property(property: 'type', description: '售后类型:1=仅退款,2=退货退款', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'amount', description: '退款金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'reason', description: '退款原因', type: 'string')]
    private string $reason;

    #[OA\Property(property: 'adminNote', description: '商家审核备注/拒绝原因', type: 'string')]
    private string $adminNote;

    #[OA\Property(property: 'auditTime', description: '审核时间', type: 'string')]
    private string $auditTime;

    #[OA\Property(property: 'operatorId', description: '审核操作人ID,关联admin_users', type: 'integer')]
    private int $operatorId;

    #[OA\Property(property: 'images', description: '申请凭证图片(JSON数组)', type: 'string')]
    private string $images;

    #[OA\Property(property: 'returnExpressCompany', description: '退货物流公司,退货退款时填写', type: 'string')]
    private string $returnExpressCompany;

    #[OA\Property(property: 'returnExpressNo', description: '退货物流单号', type: 'string')]
    private string $returnExpressNo;

    #[OA\Property(property: 'status', description: '状态:0=待审核,1=退款中,2=已退款,3=已拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'refundedAt', description: '退款完成时间', type: 'string')]
    private string $refundedAt;

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
     * 获取关联支付单ID(payments 表)
     */
    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    /**
     * 设置关联支付单ID(payments 表)
     */
    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    /**
     * 获取售后类型:1=仅退款,2=退货退款
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置售后类型:1=仅退款,2=退货退款
     */
    public function setType(int $type): void
    {
        $this->type = $type;
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
     * 获取商家审核备注/拒绝原因
     */
    public function getAdminNote(): string
    {
        return $this->adminNote;
    }

    /**
     * 设置商家审核备注/拒绝原因
     */
    public function setAdminNote(string $adminNote): void
    {
        $this->adminNote = $adminNote;
    }

    /**
     * 获取审核时间
     */
    public function getAuditTime(): string
    {
        return $this->auditTime;
    }

    /**
     * 设置审核时间
     */
    public function setAuditTime(string $auditTime): void
    {
        $this->auditTime = $auditTime;
    }

    /**
     * 获取审核操作人ID,关联admin_users
     */
    public function getOperatorId(): int
    {
        return $this->operatorId;
    }

    /**
     * 设置审核操作人ID,关联admin_users
     */
    public function setOperatorId(int $operatorId): void
    {
        $this->operatorId = $operatorId;
    }

    /**
     * 获取申请凭证图片(JSON数组)
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * 设置申请凭证图片(JSON数组)
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * 获取退货物流公司,退货退款时填写
     */
    public function getReturnExpressCompany(): string
    {
        return $this->returnExpressCompany;
    }

    /**
     * 设置退货物流公司,退货退款时填写
     */
    public function setReturnExpressCompany(string $returnExpressCompany): void
    {
        $this->returnExpressCompany = $returnExpressCompany;
    }

    /**
     * 获取退货物流单号
     */
    public function getReturnExpressNo(): string
    {
        return $this->returnExpressNo;
    }

    /**
     * 设置退货物流单号
     */
    public function setReturnExpressNo(string $returnExpressNo): void
    {
        $this->returnExpressNo = $returnExpressNo;
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
