<?php

declare(strict_types=1);

namespace App\Domains\Chat\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ChatSessionEntity')]
class ChatSessionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 发起用户ID

    public const string getServerId = 'server_id'; // 接待客服管理员ID,0=未分配(机器人/排队)

    public const string getSourceType = 'source_type'; // 咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询

    public const string getSourceId = 'source_id'; // 来源ID,商品ID或订单ID

    public const string getStatus = 'status'; // 状态:1=进行中,2=已结束

    public const string getUserLastReadId = 'user_last_read_id'; // 用户已读消息水位(消息ID),会话级已读

    public const string getServerLastReadId = 'server_last_read_id'; // 客服已读消息水位(消息ID)

    public const string getRating = 'rating'; // 满意度评分:0=未评,1-5星

    public const string getRatedAt = 'rated_at'; // 评分时间

    public const string getAssignedAt = 'assigned_at'; // 分配客服时间

    public const string getClosedAt = 'closed_at'; // 结束时间

    public const string getCloseReason = 'close_reason'; // 结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '发起用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'serverId', description: '接待客服管理员ID,0=未分配(机器人/排队)', type: 'integer')]
    private int $serverId;

    #[OA\Property(property: 'sourceType', description: '咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询', type: 'string')]
    private string $sourceType;

    #[OA\Property(property: 'sourceId', description: '来源ID,商品ID或订单ID', type: 'integer')]
    private int $sourceId;

    #[OA\Property(property: 'status', description: '状态:1=进行中,2=已结束', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'userLastReadId', description: '用户已读消息水位(消息ID),会话级已读', type: 'integer')]
    private int $userLastReadId;

    #[OA\Property(property: 'serverLastReadId', description: '客服已读消息水位(消息ID)', type: 'integer')]
    private int $serverLastReadId;

    #[OA\Property(property: 'rating', description: '满意度评分:0=未评,1-5星', type: 'integer')]
    private int $rating;

    #[OA\Property(property: 'ratedAt', description: '评分时间', type: 'string')]
    private string $ratedAt;

    #[OA\Property(property: 'assignedAt', description: '分配客服时间', type: 'string')]
    private string $assignedAt;

    #[OA\Property(property: 'closedAt', description: '结束时间', type: 'string')]
    private string $closedAt;

    #[OA\Property(property: 'closeReason', description: '结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭', type: 'string')]
    private string $closeReason;

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
     * 获取发起用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置发起用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取接待客服管理员ID,0=未分配(机器人/排队)
     */
    public function getServerId(): int
    {
        return $this->serverId;
    }

    /**
     * 设置接待客服管理员ID,0=未分配(机器人/排队)
     */
    public function setServerId(int $serverId): void
    {
        $this->serverId = $serverId;
    }

    /**
     * 获取咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    /**
     * 设置咨询来源:product=商品,order=订单,wechat=微信入口,none=主动咨询
     */
    public function setSourceType(string $sourceType): void
    {
        $this->sourceType = $sourceType;
    }

    /**
     * 获取来源ID,商品ID或订单ID
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * 设置来源ID,商品ID或订单ID
     */
    public function setSourceId(int $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    /**
     * 获取状态:1=进行中,2=已结束
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:1=进行中,2=已结束
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取用户已读消息水位(消息ID),会话级已读
     */
    public function getUserLastReadId(): int
    {
        return $this->userLastReadId;
    }

    /**
     * 设置用户已读消息水位(消息ID),会话级已读
     */
    public function setUserLastReadId(int $userLastReadId): void
    {
        $this->userLastReadId = $userLastReadId;
    }

    /**
     * 获取客服已读消息水位(消息ID)
     */
    public function getServerLastReadId(): int
    {
        return $this->serverLastReadId;
    }

    /**
     * 设置客服已读消息水位(消息ID)
     */
    public function setServerLastReadId(int $serverLastReadId): void
    {
        $this->serverLastReadId = $serverLastReadId;
    }

    /**
     * 获取满意度评分:0=未评,1-5星
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * 设置满意度评分:0=未评,1-5星
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * 获取评分时间
     */
    public function getRatedAt(): string
    {
        return $this->ratedAt;
    }

    /**
     * 设置评分时间
     */
    public function setRatedAt(string $ratedAt): void
    {
        $this->ratedAt = $ratedAt;
    }

    /**
     * 获取分配客服时间
     */
    public function getAssignedAt(): string
    {
        return $this->assignedAt;
    }

    /**
     * 设置分配客服时间
     */
    public function setAssignedAt(string $assignedAt): void
    {
        $this->assignedAt = $assignedAt;
    }

    /**
     * 获取结束时间
     */
    public function getClosedAt(): string
    {
        return $this->closedAt;
    }

    /**
     * 设置结束时间
     */
    public function setClosedAt(string $closedAt): void
    {
        $this->closedAt = $closedAt;
    }

    /**
     * 获取结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭
     */
    public function getCloseReason(): string
    {
        return $this->closeReason;
    }

    /**
     * 设置结束原因:user=用户关闭,server=客服关闭,timeout=超时自动关闭
     */
    public function setCloseReason(string $closeReason): void
    {
        $this->closeReason = $closeReason;
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
