<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserFundApplicationEntity')]
class UserFundApplicationEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getType = 'type'; // 类型:recharge=充值,withdraw=提现

    public const string getAmount = 'amount'; // 申请金额

    public const string getStatus = 'status'; // 状态:0=待审核,1=已到账,2=已拒绝

    public const string getUserNote = 'user_note'; // 用户备注

    public const string getAdminNote = 'admin_note'; // 管理员备注

    public const string getPaidAt = 'paid_at'; // 到账时间

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'type', description: '类型:recharge=充值,withdraw=提现', type: 'string')]
    private string $type;

    #[OA\Property(property: 'amount', description: '申请金额', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'status', description: '状态:0=待审核,1=已到账,2=已拒绝', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'userNote', description: '用户备注', type: 'string')]
    private string $userNote;

    #[OA\Property(property: 'adminNote', description: '管理员备注', type: 'string')]
    private string $adminNote;

    #[OA\Property(property: 'paidAt', description: '到账时间', type: 'string')]
    private string $paidAt;

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
     * 获取类型:recharge=充值,withdraw=提现
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:recharge=充值,withdraw=提现
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取申请金额
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置申请金额
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取状态:0=待审核,1=已到账,2=已拒绝
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待审核,1=已到账,2=已拒绝
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取用户备注
     */
    public function getUserNote(): string
    {
        return $this->userNote;
    }

    /**
     * 设置用户备注
     */
    public function setUserNote(string $userNote): void
    {
        $this->userNote = $userNote;
    }

    /**
     * 获取管理员备注
     */
    public function getAdminNote(): string
    {
        return $this->adminNote;
    }

    /**
     * 设置管理员备注
     */
    public function setAdminNote(string $adminNote): void
    {
        $this->adminNote = $adminNote;
    }

    /**
     * 获取到账时间
     */
    public function getPaidAt(): string
    {
        return $this->paidAt;
    }

    /**
     * 设置到账时间
     */
    public function setPaidAt(string $paidAt): void
    {
        $this->paidAt = $paidAt;
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
