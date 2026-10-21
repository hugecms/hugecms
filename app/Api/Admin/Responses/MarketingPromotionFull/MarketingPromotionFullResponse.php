<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\MarketingPromotionFull;

use App\Domains\Marketing\Entities\MarketingPromotionLadderEntity;
use App\Domains\Marketing\Entities\MarketingPromotionScopeEntity;
use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingPromotionFullResponse')]
class MarketingPromotionFullResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '活动名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'type', description: '类型:full_reduce=满减,discount=折扣', type: 'string')]
    private string $type;

    #[OA\Property(property: 'rangeType', description: '适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品', type: 'string')]
    private string $rangeType;

    #[OA\Property(property: 'scopes', description: '适用范围列表,rangeType=all 时为空', type: 'array', items: new OA\Items(ref: MarketingPromotionScopeEntity::class))]
    private array $scopes;

    #[OA\Property(property: 'ladders', description: '优惠档位列表', type: 'array', items: new OA\Items(ref: MarketingPromotionLadderEntity::class))]
    private array $ladders;

    #[OA\Property(property: 'startTime', description: '活动开始时间', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '活动结束时间', type: 'string')]
    private string $endTime;

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
     * 获取类型:full_reduce=满减,discount=折扣
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:full_reduce=满减,discount=折扣
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
     */
    public function getRangeType(): string
    {
        return $this->rangeType;
    }

    /**
     * 设置适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品
     */
    public function setRangeType(string $rangeType): void
    {
        $this->rangeType = $rangeType;
    }

    /**
     * 获取适用范围列表,rangeType=all 时为空
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * 设置适用范围列表,rangeType=all 时为空
     */
    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    /**
     * 获取优惠档位列表
     */
    public function getLadders(): array
    {
        return $this->ladders;
    }

    /**
     * 设置优惠档位列表
     */
    public function setLadders(array $ladders): void
    {
        $this->ladders = $ladders;
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
