<?php

declare(strict_types=1);

namespace App\Domains\Order\Responses\OrderShipment;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderShipmentResponse')]
class OrderShipmentResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'expressCompany', description: '快递公司', type: 'string')]
    private string $expressCompany;

    #[OA\Property(property: 'expressNo', description: '运单号', type: 'string')]
    private string $expressNo;

    #[OA\Property(property: 'remark', description: '发货备注', type: 'string')]
    private string $remark;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
    private string $createTime;

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
     * 获取快递公司
     */
    public function getExpressCompany(): string
    {
        return $this->expressCompany;
    }

    /**
     * 设置快递公司
     */
    public function setExpressCompany(string $expressCompany): void
    {
        $this->expressCompany = $expressCompany;
    }

    /**
     * 获取运单号
     */
    public function getExpressNo(): string
    {
        return $this->expressNo;
    }

    /**
     * 设置运单号
     */
    public function setExpressNo(string $expressNo): void
    {
        $this->expressNo = $expressNo;
    }

    /**
     * 获取发货备注
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置发货备注
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
