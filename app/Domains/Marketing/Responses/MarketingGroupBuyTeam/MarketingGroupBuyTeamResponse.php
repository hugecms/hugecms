<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingGroupBuyTeam;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingGroupBuyTeamResponse')]
class MarketingGroupBuyTeamResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'activityId', description: '拼团活动ID', type: 'integer')]
    private int $activityId;

    #[OA\Property(property: 'leaderUserId', description: '团长用户ID', type: 'integer')]
    private int $leaderUserId;

    #[OA\Property(property: 'status', description: '状态:1=拼团中,2=已成团,3=已解散(超时/取消)', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'memberCount', description: '当前成员数', type: 'integer')]
    private int $memberCount;

    #[OA\Property(property: 'expireTime', description: '成团截止时间,超时扫描依据', type: 'string')]
    private string $expireTime;

    #[OA\Property(property: 'successTime', description: '成团时间', type: 'string')]
    private string $successTime;

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
     * 获取拼团活动ID
     */
    public function getActivityId(): int
    {
        return $this->activityId;
    }

    /**
     * 设置拼团活动ID
     */
    public function setActivityId(int $activityId): void
    {
        $this->activityId = $activityId;
    }

    /**
     * 获取团长用户ID
     */
    public function getLeaderUserId(): int
    {
        return $this->leaderUserId;
    }

    /**
     * 设置团长用户ID
     */
    public function setLeaderUserId(int $leaderUserId): void
    {
        $this->leaderUserId = $leaderUserId;
    }

    /**
     * 获取状态:1=拼团中,2=已成团,3=已解散(超时/取消)
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1=拼团中,2=已成团,3=已解散(超时/取消)
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取当前成员数
     */
    public function getMemberCount(): int
    {
        return $this->memberCount;
    }

    /**
     * 设置当前成员数
     */
    public function setMemberCount(int $memberCount): void
    {
        $this->memberCount = $memberCount;
    }

    /**
     * 获取成团截止时间,超时扫描依据
     */
    public function getExpireTime(): string
    {
        return $this->expireTime;
    }

    /**
     * 设置成团截止时间,超时扫描依据
     */
    public function setExpireTime(string $expireTime): void
    {
        $this->expireTime = $expireTime;
    }

    /**
     * 获取成团时间
     */
    public function getSuccessTime(): string
    {
        return $this->successTime;
    }

    /**
     * 设置成团时间
     */
    public function setSuccessTime(string $successTime): void
    {
        $this->successTime = $successTime;
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
