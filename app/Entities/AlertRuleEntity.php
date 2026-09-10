<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AlertRuleEntity')]
class AlertRuleEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getRuleName = 'rule_name'; // 告警规则名称

    public const string getMetric = 'metric'; // 监控指标：queue_length/disk_usage/cpu_usage/memory_usage/error_rate

    public const string getOperator = 'operator'; // 比较操作符：&gt;/&lt;/&gt;=/&lt;=/=

    public const string getThreshold = 'threshold'; // 阈值

    public const string getDurationSeconds = 'duration_seconds'; // 持续时长（秒）

    public const string getSeverity = 'severity'; // 严重程度：warning/critical/emergency

    public const string getNotifyChannels = 'notify_channels'; // 通知方式：[&quot;email&quot;,&quot;sms&quot;,&quot;webhook&quot;]

    public const string getIsEnabled = 'is_enabled'; // 是否启用：1是，0否

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'ruleName', description: '告警规则名称', type: 'string')]
    private string $ruleName;

    #[OA\Property(property: 'metric', description: '监控指标：queue_length/disk_usage/cpu_usage/memory_usage/error_rate', type: 'string')]
    private string $metric;

    #[OA\Property(property: 'operator', description: '比较操作符：&gt;/&lt;/&gt;=/&lt;=/=', type: 'string')]
    private string $operator;

    #[OA\Property(property: 'threshold', description: '阈值', type: 'string')]
    private string $threshold;

    #[OA\Property(property: 'durationSeconds', description: '持续时长（秒）', type: 'integer')]
    private int $durationSeconds;

    #[OA\Property(property: 'severity', description: '严重程度：warning/critical/emergency', type: 'string')]
    private string $severity;

    #[OA\Property(property: 'notifyChannels', description: '通知方式：[&quot;email&quot;,&quot;sms&quot;,&quot;webhook&quot;]', type: 'string')]
    private string $notifyChannels;

    #[OA\Property(property: 'isEnabled', description: '是否启用：1是，0否', type: 'integer')]
    private int $isEnabled;

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
     * 获取告警规则名称
     */
    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    /**
     * 设置告警规则名称
     */
    public function setRuleName(string $ruleName): void
    {
        $this->ruleName = $ruleName;
    }

    /**
     * 获取监控指标：queue_length/disk_usage/cpu_usage/memory_usage/error_rate
     */
    public function getMetric(): string
    {
        return $this->metric;
    }

    /**
     * 设置监控指标：queue_length/disk_usage/cpu_usage/memory_usage/error_rate
     */
    public function setMetric(string $metric): void
    {
        $this->metric = $metric;
    }

    /**
     * 获取比较操作符：&gt;/&lt;/&gt;=/&lt;=/=
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * 设置比较操作符：&gt;/&lt;/&gt;=/&lt;=/=
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * 获取阈值
     */
    public function getThreshold(): string
    {
        return $this->threshold;
    }

    /**
     * 设置阈值
     */
    public function setThreshold(string $threshold): void
    {
        $this->threshold = $threshold;
    }

    /**
     * 获取持续时长（秒）
     */
    public function getDurationSeconds(): int
    {
        return $this->durationSeconds;
    }

    /**
     * 设置持续时长（秒）
     */
    public function setDurationSeconds(int $durationSeconds): void
    {
        $this->durationSeconds = $durationSeconds;
    }

    /**
     * 获取严重程度：warning/critical/emergency
     */
    public function getSeverity(): string
    {
        return $this->severity;
    }

    /**
     * 设置严重程度：warning/critical/emergency
     */
    public function setSeverity(string $severity): void
    {
        $this->severity = $severity;
    }

    /**
     * 获取通知方式：[&quot;email&quot;,&quot;sms&quot;,&quot;webhook&quot;]
     */
    public function getNotifyChannels(): string
    {
        return $this->notifyChannels;
    }

    /**
     * 设置通知方式：[&quot;email&quot;,&quot;sms&quot;,&quot;webhook&quot;]
     */
    public function setNotifyChannels(string $notifyChannels): void
    {
        $this->notifyChannels = $notifyChannels;
    }

    /**
     * 获取是否启用：1是，0否
     */
    public function getIsEnabled(): int
    {
        return $this->isEnabled;
    }

    /**
     * 设置是否启用：1是，0否
     */
    public function setIsEnabled(int $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
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
