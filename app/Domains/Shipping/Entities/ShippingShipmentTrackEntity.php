<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShippingShipmentTrackEntity')]
class ShippingShipmentTrackEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getShipmentId = 'shipment_id'; // 发货单ID

    public const string getAcceptTime = 'accept_time'; // 轨迹发生时间

    public const string getLocation = 'location'; // 所在城市/网点

    public const string getDescription = 'description'; // 轨迹描述,如:已签收

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shipmentId', description: '发货单ID', type: 'integer')]
    private int $shipmentId;

    #[OA\Property(property: 'acceptTime', description: '轨迹发生时间', type: 'string')]
    private string $acceptTime;

    #[OA\Property(property: 'location', description: '所在城市/网点', type: 'string')]
    private string $location;

    #[OA\Property(property: 'description', description: '轨迹描述,如:已签收', type: 'string')]
    private string $description;

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
     * 获取发货单ID
     */
    public function getShipmentId(): int
    {
        return $this->shipmentId;
    }

    /**
     * 设置发货单ID
     */
    public function setShipmentId(int $shipmentId): void
    {
        $this->shipmentId = $shipmentId;
    }

    /**
     * 获取轨迹发生时间
     */
    public function getAcceptTime(): string
    {
        return $this->acceptTime;
    }

    /**
     * 设置轨迹发生时间
     */
    public function setAcceptTime(string $acceptTime): void
    {
        $this->acceptTime = $acceptTime;
    }

    /**
     * 获取所在城市/网点
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * 设置所在城市/网点
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    /**
     * 获取轨迹描述,如:已签收
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置轨迹描述,如:已签收
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
