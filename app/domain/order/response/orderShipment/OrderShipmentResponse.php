<?php

declare(strict_types=1);

namespace app\domain\order\response\orderShipment;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderShipmentResponse')]
class OrderShipmentResponse
{
    use ArrayHelper;

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

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function getExpressCompany(): string
    {
        return $this->expressCompany;
    }

    public function setExpressCompany(string $expressCompany): void
    {
        $this->expressCompany = $expressCompany;
    }

    public function getExpressNo(): string
    {
        return $this->expressNo;
    }

    public function setExpressNo(string $expressNo): void
    {
        $this->expressNo = $expressNo;
    }

    public function getRemark(): string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
