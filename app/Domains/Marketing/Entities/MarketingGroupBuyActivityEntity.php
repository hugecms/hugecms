<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingGroupBuyActivityEntity')]
class MarketingGroupBuyActivityEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 活动名称

    public const string getSpuId = 'spu_id'; // 商品SPU ID

    public const string getGroupSize = 'group_size'; // 成团人数

    public const string getStartTime = 'start_time'; // 活动开始时间

    public const string getEndTime = 'end_time'; // 活动结束时间

    public const string getPerLimit = 'per_limit'; // 每人限购(整个活动期)

    public const string getStatus = 'status'; // 状态:0=停用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '活动名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'spuId', description: '商品SPU ID', type: 'integer')]
    private int $spuId;

    #[OA\Property(property: 'groupSize', description: '成团人数', type: 'integer')]
    private int $groupSize;

    #[OA\Property(property: 'startTime', description: '活动开始时间', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '活动结束时间', type: 'string')]
    private string $endTime;

    #[OA\Property(property: 'perLimit', description: '每人限购(整个活动期)', type: 'integer')]
    private int $perLimit;

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
     * 获取活动名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置活动名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取商品SPU ID
     */
    public function getSpuId(): int
    {
        return $this->spuId;
    }

    /**
     * 设置商品SPU ID
     */
    public function setSpuId(int $spuId): void
    {
        $this->spuId = $spuId;
    }

    /**
     * 获取成团人数
     */
    public function getGroupSize(): int
    {
        return $this->groupSize;
    }

    /**
     * 设置成团人数
     */
    public function setGroupSize(int $groupSize): void
    {
        $this->groupSize = $groupSize;
    }

    /**
     * 获取活动开始时间
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置活动开始时间
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取活动结束时间
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置活动结束时间
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取每人限购(整个活动期)
     */
    public function getPerLimit(): int
    {
        return $this->perLimit;
    }

    /**
     * 设置每人限购(整个活动期)
     */
    public function setPerLimit(int $perLimit): void
    {
        $this->perLimit = $perLimit;
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
