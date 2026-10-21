<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingAssistActivityEntity')]
class MarketingAssistActivityEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 活动名称

    public const string getRewardType = 'reward_type'; // 奖励类型:1=优惠券,2=积分

    public const string getRewardRef = 'reward_ref'; // 奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config

    public const string getRequiredCount = 'required_count'; // 所需助力人数

    public const string getPerLimit = 'per_limit'; // 每人可发起次数

    public const string getStartTime = 'start_time'; // 开始时间

    public const string getEndTime = 'end_time'; // 结束时间

    public const string getConfig = 'config'; // 扩展参数(积分数/阶梯奖励等)

    public const string getStatus = 'status'; // 状态:0=停用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '活动名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'rewardType', description: '奖励类型:1=优惠券,2=积分', type: 'integer')]
    private int $rewardType;

    #[OA\Property(property: 'rewardRef', description: '奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config', type: 'integer')]
    private int $rewardRef;

    #[OA\Property(property: 'requiredCount', description: '所需助力人数', type: 'integer')]
    private int $requiredCount;

    #[OA\Property(property: 'perLimit', description: '每人可发起次数', type: 'integer')]
    private int $perLimit;

    #[OA\Property(property: 'startTime', description: '开始时间', type: 'string')]
    private string $startTime;

    #[OA\Property(property: 'endTime', description: '结束时间', type: 'string')]
    private string $endTime;

    #[OA\Property(property: 'config', description: '扩展参数(积分数/阶梯奖励等)', type: 'string')]
    private string $config;

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
     * 获取奖励类型:1=优惠券,2=积分
     */
    public function getRewardType(): int
    {
        return $this->rewardType;
    }

    /**
     * 设置奖励类型:1=优惠券,2=积分
     */
    public function setRewardType(int $rewardType): void
    {
        $this->rewardType = $rewardType;
    }

    /**
     * 获取奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config
     */
    public function getRewardRef(): int
    {
        return $this->rewardRef;
    }

    /**
     * 设置奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config
     */
    public function setRewardRef(int $rewardRef): void
    {
        $this->rewardRef = $rewardRef;
    }

    /**
     * 获取所需助力人数
     */
    public function getRequiredCount(): int
    {
        return $this->requiredCount;
    }

    /**
     * 设置所需助力人数
     */
    public function setRequiredCount(int $requiredCount): void
    {
        $this->requiredCount = $requiredCount;
    }

    /**
     * 获取每人可发起次数
     */
    public function getPerLimit(): int
    {
        return $this->perLimit;
    }

    /**
     * 设置每人可发起次数
     */
    public function setPerLimit(int $perLimit): void
    {
        $this->perLimit = $perLimit;
    }

    /**
     * 获取开始时间
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * 设置开始时间
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * 获取结束时间
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * 设置结束时间
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * 获取扩展参数(积分数/阶梯奖励等)
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * 设置扩展参数(积分数/阶梯奖励等)
     */
    public function setConfig(string $config): void
    {
        $this->config = $config;
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
