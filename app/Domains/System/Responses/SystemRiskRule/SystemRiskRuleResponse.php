<?php

declare(strict_types=1);

namespace App\Domains\System\Responses\SystemRiskRule;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemRiskRuleResponse')]
class SystemRiskRuleResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '规则名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'ruleType', description: '规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级', type: 'integer')]
    private int $ruleType;

    #[OA\Property(property: 'conditionExpr', description: '条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}', type: 'string')]
    private string $conditionExpr;

    #[OA\Property(property: 'action', description: '处置动作:1=拦截,2=标记放行', type: 'integer')]
    private int $action;

    #[OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer')]
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
     * 获取规则名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置规则名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级
     */
    public function getRuleType(): int
    {
        return $this->ruleType;
    }

    /**
     * 设置规则类型:1=用户黑名单,2=提现高频,3=异常领券,4=佣金套利,5=休眠分级
     */
    public function setRuleType(int $ruleType): void
    {
        $this->ruleType = $ruleType;
    }

    /**
     * 获取条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}
     */
    public function getConditionExpr(): string
    {
        return $this->conditionExpr;
    }

    /**
     * 设置条件参数(JSON),如黑名单{&quot;user_ids&quot;:[1,2]}、高频{&quot;max_count&quot;:3,&quot;window_minutes&quot;:60}
     */
    public function setConditionExpr(string $conditionExpr): void
    {
        $this->conditionExpr = $conditionExpr;
    }

    /**
     * 获取处置动作:1=拦截,2=标记放行
     */
    public function getAction(): int
    {
        return $this->action;
    }

    /**
     * 设置处置动作:1=拦截,2=标记放行
     */
    public function setAction(int $action): void
    {
        $this->action = $action;
    }

    /**
     * 获取状态:0=停用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=停用,1=启用
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
