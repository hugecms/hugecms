<?php

declare(strict_types=1);

namespace App\Domains\Order\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OrderLogEntity')]
class OrderLogEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOrderId = 'order_id'; // 订单ID

    public const string getFromStatus = 'from_status'; // 变更前状态

    public const string getToStatus = 'to_status'; // 变更后状态

    public const string getOperatorType = 'operator_type'; // 操作账户类型:admin,user,system

    public const string getOperatorId = 'operator_id'; // 操作人ID

    public const string getOperator = 'operator'; // 操作人名称快照

    public const string getNote = 'note'; // 操作备注

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'orderId', description: '订单ID', type: 'integer')]
    private int $orderId;

    #[OA\Property(property: 'fromStatus', description: '变更前状态', type: 'integer')]
    private int $fromStatus;

    #[OA\Property(property: 'toStatus', description: '变更后状态', type: 'integer')]
    private int $toStatus;

    #[OA\Property(property: 'operatorType', description: '操作账户类型:admin,user,system', type: 'string')]
    private string $operatorType;

    #[OA\Property(property: 'operatorId', description: '操作人ID', type: 'integer')]
    private int $operatorId;

    #[OA\Property(property: 'operator', description: '操作人名称快照', type: 'string')]
    private string $operator;

    #[OA\Property(property: 'note', description: '操作备注', type: 'string')]
    private string $note;

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
     * 获取订单ID
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * 获取变更前状态
     */
    public function getFromStatus(): int
    {
        return $this->fromStatus;
    }

    /**
     * 设置变更前状态
     */
    public function setFromStatus(int $fromStatus): void
    {
        $this->fromStatus = $fromStatus;
    }

    /**
     * 获取变更后状态
     */
    public function getToStatus(): int
    {
        return $this->toStatus;
    }

    /**
     * 设置变更后状态
     */
    public function setToStatus(int $toStatus): void
    {
        $this->toStatus = $toStatus;
    }

    /**
     * 获取操作账户类型:admin,user,system
     */
    public function getOperatorType(): string
    {
        return $this->operatorType;
    }

    /**
     * 设置操作账户类型:admin,user,system
     */
    public function setOperatorType(string $operatorType): void
    {
        $this->operatorType = $operatorType;
    }

    /**
     * 获取操作人ID
     */
    public function getOperatorId(): int
    {
        return $this->operatorId;
    }

    /**
     * 设置操作人ID
     */
    public function setOperatorId(int $operatorId): void
    {
        $this->operatorId = $operatorId;
    }

    /**
     * 获取操作人名称快照
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * 设置操作人名称快照
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * 获取操作备注
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * 设置操作备注
     */
    public function setNote(string $note): void
    {
        $this->note = $note;
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
