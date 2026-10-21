<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingAssistRecordEntity')]
class MarketingAssistRecordEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getActivityId = 'activity_id'; // 助力活动ID

    public const string getUserId = 'user_id'; // 发起人用户ID

    public const string getHelperCount = 'helper_count'; // 已助力人数,达required_count触发发奖

    public const string getStatus = 'status'; // 状态:1=进行中,2=已完成发奖,3=已过期

    public const string getFinishTime = 'finish_time'; // 完成时间

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'activityId', description: '助力活动ID', type: 'integer')]
    private int $activityId;

    #[OA\Property(property: 'userId', description: '发起人用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'helperCount', description: '已助力人数,达required_count触发发奖', type: 'integer')]
    private int $helperCount;

    #[OA\Property(property: 'status', description: '状态:1=进行中,2=已完成发奖,3=已过期', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'finishTime', description: '完成时间', type: 'string')]
    private string $finishTime;

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
     * 获取助力活动ID
     */
    public function getActivityId(): int
    {
        return $this->activityId;
    }

    /**
     * 设置助力活动ID
     */
    public function setActivityId(int $activityId): void
    {
        $this->activityId = $activityId;
    }

    /**
     * 获取发起人用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置发起人用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取已助力人数,达required_count触发发奖
     */
    public function getHelperCount(): int
    {
        return $this->helperCount;
    }

    /**
     * 设置已助力人数,达required_count触发发奖
     */
    public function setHelperCount(int $helperCount): void
    {
        $this->helperCount = $helperCount;
    }

    /**
     * 获取状态:1=进行中,2=已完成发奖,3=已过期
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1=进行中,2=已完成发奖,3=已过期
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取完成时间
     */
    public function getFinishTime(): string
    {
        return $this->finishTime;
    }

    /**
     * 设置完成时间
     */
    public function setFinishTime(string $finishTime): void
    {
        $this->finishTime = $finishTime;
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
