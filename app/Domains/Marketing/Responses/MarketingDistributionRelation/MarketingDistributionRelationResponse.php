<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingDistributionRelation;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingDistributionRelationResponse')]
class MarketingDistributionRelationResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '被绑定用户ID(下单人)', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'distributorId', description: '分销员用户ID', type: 'integer')]
    private int $distributorId;

    #[OA\Property(property: 'level', description: '层级:1=一级(直接邀请),2=二级(间接)', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'createTime', description: '绑定时间', type: 'string')]
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
     * 获取被绑定用户ID(下单人)
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置被绑定用户ID(下单人)
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取分销员用户ID
     */
    public function getDistributorId(): int
    {
        return $this->distributorId;
    }

    /**
     * 设置分销员用户ID
     */
    public function setDistributorId(int $distributorId): void
    {
        $this->distributorId = $distributorId;
    }

    /**
     * 获取层级:1=一级(直接邀请),2=二级(间接)
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置层级:1=一级(直接邀请),2=二级(间接)
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取绑定时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置绑定时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
