<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Responses\MarketingDistributionUser;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MarketingDistributionUserResponse')]
class MarketingDistributionUserResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'inviterId', description: '上级分销员用户ID,0=无上级', type: 'integer')]
    private int $inviterId;

    #[OA\Property(property: 'level', description: '分销员等级:1=普通,2=高级', type: 'integer')]
    private int $level;

    #[OA\Property(property: 'totalCommission', description: '累计佣金', type: 'string')]
    private string $totalCommission;

    #[OA\Property(property: 'settledCommission', description: '已结算佣金,可提现=total-settled', type: 'string')]
    private string $settledCommission;

    #[OA\Property(property: 'status', description: '状态:0=待审核,1=通过,2=拒绝,3=冻结', type: 'integer')]
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
     * 获取上级分销员用户ID,0=无上级
     */
    public function getInviterId(): int
    {
        return $this->inviterId;
    }

    /**
     * 设置上级分销员用户ID,0=无上级
     */
    public function setInviterId(int $inviterId): void
    {
        $this->inviterId = $inviterId;
    }

    /**
     * 获取分销员等级:1=普通,2=高级
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * 设置分销员等级:1=普通,2=高级
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * 获取累计佣金
     */
    public function getTotalCommission(): string
    {
        return $this->totalCommission;
    }

    /**
     * 设置累计佣金
     */
    public function setTotalCommission(string $totalCommission): void
    {
        $this->totalCommission = $totalCommission;
    }

    /**
     * 获取已结算佣金,可提现=total-settled
     */
    public function getSettledCommission(): string
    {
        return $this->settledCommission;
    }

    /**
     * 设置已结算佣金,可提现=total-settled
     */
    public function setSettledCommission(string $settledCommission): void
    {
        $this->settledCommission = $settledCommission;
    }

    /**
     * 获取状态:0=待审核,1=通过,2=拒绝,3=冻结
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待审核,1=通过,2=拒绝,3=冻结
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
