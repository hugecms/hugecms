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

    #[OA\Property(property: 'bizType', description: '业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整', type: 'integer')]
    private int $bizType;

    #[OA\Property(property: 'amount', description: '变动金额,正数入负数出', type: 'string')]
    private string $amount;

    #[OA\Property(property: 'balanceAfter', description: '变动后余额快照', type: 'string')]
    private string $balanceAfter;

    #[OA\Property(property: 'frozenAfter', description: '变动后冻结快照', type: 'string')]
    private string $frozenAfter;

    #[OA\Property(property: 'bizNo', description: '关联业务单号,如提现单号/佣金记录号/充值单号', type: 'string')]
    private string $bizNo;

    #[OA\Property(property: 'description', description: '变动说明', type: 'string')]
    private string $description;

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
     * 获取业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整
     */
    public function getBizType(): int
    {
        return $this->bizType;
    }

    /**
     * 设置业务类型:1=佣金入账,2=提现冻结,3=提现完成,4=提现失败回退,5=冲销扣回,6=余额消费冻结,7=余额消费完成,8=余额消费退回,9=充值入账,10=人工调整
     */
    public function setBizType(int $bizType): void
    {
        $this->bizType = $bizType;
    }

    /**
     * 获取变动金额,正数入负数出
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * 设置变动金额,正数入负数出
     */
    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * 获取变动后余额快照
     */
    public function getBalanceAfter(): string
    {
        return $this->balanceAfter;
    }

    /**
     * 设置变动后余额快照
     */
    public function setBalanceAfter(string $balanceAfter): void
    {
        $this->balanceAfter = $balanceAfter;
    }

    /**
     * 获取变动后冻结快照
     */
    public function getFrozenAfter(): string
    {
        return $this->frozenAfter;
    }

    /**
     * 设置变动后冻结快照
     */
    public function setFrozenAfter(string $frozenAfter): void
    {
        $this->frozenAfter = $frozenAfter;
    }

    /**
     * 获取关联业务单号,如提现单号/佣金记录号/充值单号
     */
    public function getBizNo(): string
    {
        return $this->bizNo;
    }

    /**
     * 设置关联业务单号,如提现单号/佣金记录号/充值单号
     */
    public function setBizNo(string $bizNo): void
    {
        $this->bizNo = $bizNo;
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
