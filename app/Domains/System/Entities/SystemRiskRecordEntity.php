<?php

declare(strict_types=1);

namespace App\Domains\System\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemRiskRecordEntity')]
class SystemRiskRecordEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 触发用户ID

    public const string getRuleId = 'rule_id'; // 命中规则ID,关联system_risk_rules

    public const string getObjectType = 'object_type'; // 对象类型:1=订单,2=优惠券,3=提现,4=售后

    public const string getObjectNo = 'object_no'; // 对象单号,如提现单号/订单号

    public const string getAction = 'action'; // 处置快照:1=拦截,2=标记放行

    public const string getAppealStatus = 'appeal_status'; // 申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回

    public const string getAppealNote = 'appeal_note'; // 申诉理由

    public const string getHandleNote = 'handle_note'; // 申诉处理说明

    public const string getHandledAt = 'handled_at'; // 申诉处理时间

    public const string getRemark = 'remark'; // 命中详情,如规则参数与实际值

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '触发用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'ruleId', description: '命中规则ID,关联system_risk_rules', type: 'integer')]
    private int $ruleId;

    #[OA\Property(property: 'objectType', description: '对象类型:1=订单,2=优惠券,3=提现,4=售后', type: 'integer')]
    private int $objectType;

    #[OA\Property(property: 'objectNo', description: '对象单号,如提现单号/订单号', type: 'string')]
    private string $objectNo;

    #[OA\Property(property: 'action', description: '处置快照:1=拦截,2=标记放行', type: 'integer')]
    private int $action;

    #[OA\Property(property: 'appealStatus', description: '申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回', type: 'integer')]
    private int $appealStatus;

    #[OA\Property(property: 'appealNote', description: '申诉理由', type: 'string')]
    private string $appealNote;

    #[OA\Property(property: 'handleNote', description: '申诉处理说明', type: 'string')]
    private string $handleNote;

    #[OA\Property(property: 'handledAt', description: '申诉处理时间', type: 'string')]
    private ?string $handledAt;

    #[OA\Property(property: 'remark', description: '命中详情,如规则参数与实际值', type: 'string')]
    private string $remark;

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
     * 获取触发用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置触发用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取命中规则ID,关联system_risk_rules
     */
    public function getRuleId(): int
    {
        return $this->ruleId;
    }

    /**
     * 设置命中规则ID,关联system_risk_rules
     */
    public function setRuleId(int $ruleId): void
    {
        $this->ruleId = $ruleId;
    }

    /**
     * 获取对象类型:1=订单,2=优惠券,3=提现,4=售后
     */
    public function getObjectType(): int
    {
        return $this->objectType;
    }

    /**
     * 设置对象类型:1=订单,2=优惠券,3=提现,4=售后
     */
    public function setObjectType(int $objectType): void
    {
        $this->objectType = $objectType;
    }

    /**
     * 获取对象单号,如提现单号/订单号
     */
    public function getObjectNo(): string
    {
        return $this->objectNo;
    }

    /**
     * 设置对象单号,如提现单号/订单号
     */
    public function setObjectNo(string $objectNo): void
    {
        $this->objectNo = $objectNo;
    }

    /**
     * 获取处置快照:1=拦截,2=标记放行
     */
    public function getAction(): int
    {
        return $this->action;
    }

    /**
     * 设置处置快照:1=拦截,2=标记放行
     */
    public function setAction(int $action): void
    {
        $this->action = $action;
    }

    /**
     * 获取申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回
     */
    public function getAppealStatus(): int
    {
        return $this->appealStatus;
    }

    /**
     * 设置申诉状态:0=无,1=申诉中,2=申诉通过,3=申诉驳回
     */
    public function setAppealStatus(int $appealStatus): void
    {
        $this->appealStatus = $appealStatus;
    }

    /**
     * 获取申诉理由
     */
    public function getAppealNote(): string
    {
        return $this->appealNote;
    }

    /**
     * 设置申诉理由
     */
    public function setAppealNote(string $appealNote): void
    {
        $this->appealNote = $appealNote;
    }

    /**
     * 获取申诉处理说明
     */
    public function getHandleNote(): string
    {
        return $this->handleNote;
    }

    /**
     * 设置申诉处理说明
     */
    public function setHandleNote(string $handleNote): void
    {
        $this->handleNote = $handleNote;
    }

    /**
     * 获取申诉处理时间
     */
    public function getHandledAt(): ?string
    {
        return $this->handledAt;
    }

    /**
     * 设置申诉处理时间
     */
    public function setHandledAt(?string $handledAt): void
    {
        $this->handledAt = $handledAt;
    }

    /**
     * 获取命中详情,如规则参数与实际值
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置命中详情,如规则参数与实际值
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
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
