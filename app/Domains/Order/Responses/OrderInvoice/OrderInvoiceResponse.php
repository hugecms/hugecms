<?php

declare(strict_types=1);

namespace App\Domains\Order\Responses\OrderInvoice;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderInvoiceResponse')]
class OrderInvoiceResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'type', description: '类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'title', description: '发票抬头', type: 'string')]
    private string $title;

    #[OA\Property(property: 'taxNo', description: '纳税人识别号,个人抬头为空', type: 'string')]
    private string $taxNo;

    #[OA\Property(property: 'amount', description: '开票金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'status', description: '状态:0=待开票,1=已开票,2=已作废', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'invoiceNo', description: '发票号码', type: 'string')]
    private string $invoiceNo;

    #[OA\Property(property: 'invoicedAt', description: '开票时间', type: 'string')]
    private string $invoicedAt;

    #[OA\Property(property: 'remark', description: '备注', type: 'string')]
    private string $remark;

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
     * 获取类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置类型:1=电子普通发票,2=纸质普通发票,3=增值税专用发票
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取发票抬头
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置发票抬头
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取纳税人识别号,个人抬头为空
     */
    public function getTaxNo(): string
    {
        return $this->taxNo;
    }

    /**
     * 设置纳税人识别号,个人抬头为空
     */
    public function setTaxNo(string $taxNo): void
    {
        $this->taxNo = $taxNo;
    }

    /**
     * 获取开票金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置开票金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取状态:0=待开票,1=已开票,2=已作废
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待开票,1=已开票,2=已作废
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取发票号码
     */
    public function getInvoiceNo(): string
    {
        return $this->invoiceNo;
    }

    /**
     * 设置发票号码
     */
    public function setInvoiceNo(string $invoiceNo): void
    {
        $this->invoiceNo = $invoiceNo;
    }

    /**
     * 获取开票时间
     */
    public function getInvoicedAt(): string
    {
        return $this->invoicedAt;
    }

    /**
     * 设置开票时间
     */
    public function setInvoicedAt(string $invoicedAt): void
    {
        $this->invoicedAt = $invoicedAt;
    }

    /**
     * 获取备注
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置备注
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
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
