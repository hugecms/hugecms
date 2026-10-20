<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\UserAccountLog;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserAccountLogResponse')]
class UserAccountLogResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'changeType', description: '变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整', type: 'string')]
    private string $changeType;

    #[OA\Property(property: 'changeAmount', description: '资金变动,正数入负数出', type: 'string')]
    private string $changeAmount;

    #[OA\Property(property: 'changePoints', description: '积分变动,正数入负数出', type: 'integer')]
    private int $changePoints;

    #[OA\Property(property: 'description', description: '变动说明', type: 'string')]
    private string $description;

    #[OA\Property(property: 'relatedId', description: '关联单据ID', type: 'integer')]
    private int $relatedId;

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
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整
     */
    public function getChangeType(): string
    {
        return $this->changeType;
    }

    /**
     * 设置变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整
     */
    public function setChangeType(string $changeType): void
    {
        $this->changeType = $changeType;
    }

    /**
     * 获取资金变动,正数入负数出
     */
    public function getChangeAmount(): string
    {
        return $this->changeAmount;
    }

    /**
     * 设置资金变动,正数入负数出
     */
    public function setChangeAmount(string $changeAmount): void
    {
        $this->changeAmount = $changeAmount;
    }

    /**
     * 获取积分变动,正数入负数出
     */
    public function getChangePoints(): int
    {
        return $this->changePoints;
    }

    /**
     * 设置积分变动,正数入负数出
     */
    public function setChangePoints(int $changePoints): void
    {
        $this->changePoints = $changePoints;
    }

    /**
     * 获取变动说明
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置变动说明
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取关联单据ID
     */
    public function getRelatedId(): int
    {
        return $this->relatedId;
    }

    /**
     * 设置关联单据ID
     */
    public function setRelatedId(int $relatedId): void
    {
        $this->relatedId = $relatedId;
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
