<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingGroupBuyTeamMember;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingGroupBuyTeamMemberResponse')]
class MarketingGroupBuyTeamMemberResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'teamId', description: '团ID', type: 'integer')]
    private int $teamId;

    #[OA\Property(property: 'userId', description: '成员用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'orderNo', description: '成员订单号,关联orders.order_no', type: 'string')]
    private string $orderNo;

    #[OA\Property(property: 'joinTime', description: '参团时间', type: 'string')]
    private string $joinTime;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
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
     * 获取团ID
     */
    public function getTeamId(): int
    {
        return $this->teamId;
    }

    /**
     * 设置团ID
     */
    public function setTeamId(int $teamId): void
    {
        $this->teamId = $teamId;
    }

    /**
     * 获取成员用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置成员用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取成员订单号,关联orders.order_no
     */
    public function getOrderNo(): string
    {
        return $this->orderNo;
    }

    /**
     * 设置成员订单号,关联orders.order_no
     */
    public function setOrderNo(string $orderNo): void
    {
        $this->orderNo = $orderNo;
    }

    /**
     * 获取参团时间
     */
    public function getJoinTime(): string
    {
        return $this->joinTime;
    }

    /**
     * 设置参团时间
     */
    public function setJoinTime(string $joinTime): void
    {
        $this->joinTime = $joinTime;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
