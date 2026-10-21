<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingCommissionRuleEntity')]
class MarketingCommissionRuleEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getScopeType = 'scope_type'; // 范围类型:1=分类默认,2=商品覆盖

    public const string getScopeId = 'scope_id'; // 目标ID,scope_type=1指向分类,2指向商品

    public const string getLevel1Rate = 'level1_rate'; // 一级佣金比例(%),如5.00表示5%

    public const string getLevel2Rate = 'level2_rate'; // 二级佣金比例(%)

    public const string getStatus = 'status'; // 状态:0=停用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'scopeType', description: '范围类型:1=分类默认,2=商品覆盖', type: 'integer')]
    private int $scopeType;

    #[OA\Property(property: 'scopeId', description: '目标ID,scope_type=1指向分类,2指向商品', type: 'integer')]
    private int $scopeId;

    #[OA\Property(property: 'level1Rate', description: '一级佣金比例(%),如5.00表示5%', type: 'string')]
    private string $level1Rate;

    #[OA\Property(property: 'level2Rate', description: '二级佣金比例(%)', type: 'string')]
    private string $level2Rate;

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
     * 获取范围类型:1=分类默认,2=商品覆盖
     */
    public function getScopeType(): int
    {
        return $this->scopeType;
    }

    /**
     * 设置范围类型:1=分类默认,2=商品覆盖
     */
    public function setScopeType(int $scopeType): void
    {
        $this->scopeType = $scopeType;
    }

    /**
     * 获取目标ID,scope_type=1指向分类,2指向商品
     */
    public function getScopeId(): int
    {
        return $this->scopeId;
    }

    /**
     * 设置目标ID,scope_type=1指向分类,2指向商品
     */
    public function setScopeId(int $scopeId): void
    {
        $this->scopeId = $scopeId;
    }

    /**
     * 获取一级佣金比例(%),如5.00表示5%
     */
    public function getLevel1Rate(): string
    {
        return $this->level1Rate;
    }

    /**
     * 设置一级佣金比例(%),如5.00表示5%
     */
    public function setLevel1Rate(string $level1Rate): void
    {
        $this->level1Rate = $level1Rate;
    }

    /**
     * 获取二级佣金比例(%)
     */
    public function getLevel2Rate(): string
    {
        return $this->level2Rate;
    }

    /**
     * 设置二级佣金比例(%)
     */
    public function setLevel2Rate(string $level2Rate): void
    {
        $this->level2Rate = $level2Rate;
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
