<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingBargainItem;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingBargainItemResponse')]
class MarketingBargainItemResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'activityId', description: '砍价活动ID', type: 'integer')]
    private int $activityId;

    #[OA\Property(property: 'skuId', description: 'SKU ID', type: 'integer')]
    private int $skuId;

    #[OA\Property(property: 'originalPrice', description: '起始价(发起时售价口径)', type: 'string')]
    private string $originalPrice;

    #[OA\Property(property: 'floorPrice', description: '底价(砍到底价即可下单;floor&lt;=original 应用校验)', type: 'string')]
    private string $floorPrice;

    #[OA\Property(property: 'maxCutCount', description: '最大帮砍刀数,0=不限,金额收敛到底价', type: 'integer')]
    private int $maxCutCount;

    #[OA\Property(property: 'config', description: '玩法参数(随机递减算法边界/首刀上限等,应用层约定)', type: 'string')]
    private string $config;

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
     * 获取砍价活动ID
     */
    public function getActivityId(): int
    {
        return $this->activityId;
    }

    /**
     * 设置砍价活动ID
     */
    public function setActivityId(int $activityId): void
    {
        $this->activityId = $activityId;
    }

    /**
     * 获取SKU ID
     */
    public function getSkuId(): int
    {
        return $this->skuId;
    }

    /**
     * 设置SKU ID
     */
    public function setSkuId(int $skuId): void
    {
        $this->skuId = $skuId;
    }

    /**
     * 获取起始价(发起时售价口径)
     */
    public function getOriginalPrice(): string
    {
        return $this->originalPrice;
    }

    /**
     * 设置起始价(发起时售价口径)
     */
    public function setOriginalPrice(string $originalPrice): void
    {
        $this->originalPrice = $originalPrice;
    }

    /**
     * 获取底价(砍到底价即可下单;floor&lt;=original 应用校验)
     */
    public function getFloorPrice(): string
    {
        return $this->floorPrice;
    }

    /**
     * 设置底价(砍到底价即可下单;floor&lt;=original 应用校验)
     */
    public function setFloorPrice(string $floorPrice): void
    {
        $this->floorPrice = $floorPrice;
    }

    /**
     * 获取最大帮砍刀数,0=不限,金额收敛到底价
     */
    public function getMaxCutCount(): int
    {
        return $this->maxCutCount;
    }

    /**
     * 设置最大帮砍刀数,0=不限,金额收敛到底价
     */
    public function setMaxCutCount(int $maxCutCount): void
    {
        $this->maxCutCount = $maxCutCount;
    }

    /**
     * 获取玩法参数(随机递减算法边界/首刀上限等,应用层约定)
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * 设置玩法参数(随机递减算法边界/首刀上限等,应用层约定)
     */
    public function setConfig(string $config): void
    {
        $this->config = $config;
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
