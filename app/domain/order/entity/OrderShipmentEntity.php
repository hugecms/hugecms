<?php

declare(strict_types=1);

namespace app\domain\order\entity;

use Juling\Foundation\Support\ArrayHelper;

class OrderShipmentEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getExpressCompany = 'express_company'; // 快递公司

    public const string getExpressNo = 'express_no'; // 运单号

    public const string getRemark = 'remark'; // 发货备注

    public const string getCreateTime = 'create_time'; // 

    private int $id;

    private int $orderId;

    private string $expressCompany;

    private string $expressNo;

    private string $remark;

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
