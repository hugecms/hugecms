<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingPromotionScopeEntity')]
class MarketingPromotionScopeEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getPromotionId = 'promotion_id'; // 促销活动ID

    public const string getScopeType = 'scope_type'; // 范围类型:category=分类,brand=品牌,product=商品

    public const string getTargetId = 'target_id'; // 目标ID

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'promotionId', description: '促销活动ID', type: 'integer')]
    private int $promotionId;

    #[OA\Property(property: 'scopeType', description: '范围类型:category=分类,brand=品牌,product=商品', type: 'string')]
    private string $scopeType;

    #[OA\Property(property: 'targetId', description: '目标ID', type: 'integer')]
    private int $targetId;

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
     * 获取促销活动ID
     */
    public function getPromotionId(): int
    {
        return $this->promotionId;
    }

    /**
     * 设置促销活动ID
     */
    public function setPromotionId(int $promotionId): void
    {
        $this->promotionId = $promotionId;
    }

    /**
     * 获取范围类型:category=分类,brand=品牌,product=商品
     */
    public function getScopeType(): string
    {
        return $this->scopeType;
    }

    /**
     * 设置范围类型:category=分类,brand=品牌,product=商品
     */
    public function setScopeType(string $scopeType): void
    {
        $this->scopeType = $scopeType;
    }

    /**
     * 获取目标ID
     */
    public function getTargetId(): int
    {
        return $this->targetId;
    }

    /**
     * 设置目标ID
     */
    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
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
