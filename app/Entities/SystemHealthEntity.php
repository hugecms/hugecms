<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemHealthEntity')]
class SystemHealthEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getCheckType = 'check_type'; // 检查类型：database/redis/queue/storage/api/disk_space

    public const string getCheckStatus = 'check_status'; // 状态：healthy/warning/critical/unknown

    public const string getCheckValue = 'check_value'; // 检查值（如：磁盘使用率85%）

    public const string getThreshold = 'threshold'; // 阈值配置

    public const string getErrorMessage = 'error_message'; // 异常信息

    public const string getCheckedAt = 'checked_at'; // 检查时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'checkType', description: '检查类型：database/redis/queue/storage/api/disk_space', type: 'string')]
    private string $checkType;

    #[OA\Property(property: 'checkStatus', description: '状态：healthy/warning/critical/unknown', type: 'string')]
    private string $checkStatus;

    #[OA\Property(property: 'checkValue', description: '检查值（如：磁盘使用率85%）', type: 'string')]
    private string $checkValue;

    #[OA\Property(property: 'threshold', description: '阈值配置', type: 'string')]
    private string $threshold;

    #[OA\Property(property: 'errorMessage', description: '异常信息', type: 'string')]
    private string $errorMessage;

    #[OA\Property(property: 'checkedAt', description: '检查时间', type: 'string')]
    private string $checkedAt;

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
     * 获取检查类型：database/redis/queue/storage/api/disk_space
     */
    public function getCheckType(): string
    {
        return $this->checkType;
    }

    /**
     * 设置检查类型：database/redis/queue/storage/api/disk_space
     */
    public function setCheckType(string $checkType): void
    {
        $this->checkType = $checkType;
    }

    /**
     * 获取状态：healthy/warning/critical/unknown
     */
    public function getCheckStatus(): string
    {
        return $this->checkStatus;
    }

    /**
     * 设置状态：healthy/warning/critical/unknown
     */
    public function setCheckStatus(string $checkStatus): void
    {
        $this->checkStatus = $checkStatus;
    }

    /**
     * 获取检查值（如：磁盘使用率85%）
     */
    public function getCheckValue(): string
    {
        return $this->checkValue;
    }

    /**
     * 设置检查值（如：磁盘使用率85%）
     */
    public function setCheckValue(string $checkValue): void
    {
        $this->checkValue = $checkValue;
    }

    /**
     * 获取阈值配置
     */
    public function getThreshold(): string
    {
        return $this->threshold;
    }

    /**
     * 设置阈值配置
     */
    public function setThreshold(string $threshold): void
    {
        $this->threshold = $threshold;
    }

    /**
     * 获取异常信息
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * 设置异常信息
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * 获取检查时间
     */
    public function getCheckedAt(): string
    {
        return $this->checkedAt;
    }

    /**
     * 设置检查时间
     */
    public function setCheckedAt(string $checkedAt): void
    {
        $this->checkedAt = $checkedAt;
    }
}
