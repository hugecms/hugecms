<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShippingAreaEntity')]
class ShippingAreaEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getShippingId = 'shipping_id'; // 配送方式ID

    public const string getName = 'name'; // 区域名称,如:华东地区

    public const string getFeeConfig = 'fee_config'; // 运价配置(JSON):首重/续重/首费/续费/固定运费等

    public const string getStatus = 'status'; // 状态:0=停用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shippingId', description: '配送方式ID', type: 'integer')]
    private int $shippingId;

    #[OA\Property(property: 'name', description: '区域名称,如:华东地区', type: 'string')]
    private string $name;

    #[OA\Property(property: 'feeConfig', description: '运价配置(JSON):首重/续重/首费/续费/固定运费等', type: 'string')]
    private string $feeConfig;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
    private int $status;

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
     * 获取配送方式ID
     */
    public function getShippingId(): int
    {
        return $this->shippingId;
    }

    /**
     * 设置配送方式ID
     */
    public function setShippingId(int $shippingId): void
    {
        $this->shippingId = $shippingId;
    }

    /**
     * 获取区域名称,如:华东地区
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置区域名称,如:华东地区
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取运价配置(JSON):首重/续重/首费/续费/固定运费等
     */
    public function getFeeConfig(): string
    {
        return $this->feeConfig;
    }

    /**
     * 设置运价配置(JSON):首重/续重/首费/续费/固定运费等
     */
    public function setFeeConfig(string $feeConfig): void
    {
        $this->feeConfig = $feeConfig;
    }

    /**
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
