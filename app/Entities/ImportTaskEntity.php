<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ImportTaskEntity')]
class ImportTaskEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOperatorId = 'operator_id'; // 操作人ID

    public const string getTaskName = 'task_name'; // 任务名称

    public const string getImportType = 'import_type'; // 导入类型：content/user/term/attachment/comment

    public const string getSourceType = 'source_type'; // 来源类型：csv/excel/wordpress/json/api

    public const string getSourceFile = 'source_file'; // 源文件路径

    public const string getConfig = 'config'; // 导入配置（字段映射、校验规则）

    public const string getTotalRecords = 'total_records'; // 总记录数

    public const string getSuccessRecords = 'success_records'; // 成功导入数

    public const string getFailedRecords = 'failed_records'; // 失败数

    public const string getErrorLog = 'error_log'; // 错误详情（JSON数组）

    public const string getStatus = 'status'; // 状态：pending/processing/completed/failed（执行走 Laravel 队列）

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    public const string getCompletedAt = 'completed_at'; // 完成时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'operatorId', description: '操作人ID', type: 'integer')]
    private int $operatorId;

    #[OA\Property(property: 'taskName', description: '任务名称', type: 'string')]
    private string $taskName;

    #[OA\Property(property: 'importType', description: '导入类型：content/user/term/attachment/comment', type: 'string')]
    private string $importType;

    #[OA\Property(property: 'sourceType', description: '来源类型：csv/excel/wordpress/json/api', type: 'string')]
    private string $sourceType;

    #[OA\Property(property: 'sourceFile', description: '源文件路径', type: 'string')]
    private string $sourceFile;

    #[OA\Property(property: 'config', description: '导入配置（字段映射、校验规则）', type: 'string')]
    private string $config;

    #[OA\Property(property: 'totalRecords', description: '总记录数', type: 'integer')]
    private int $totalRecords;

    #[OA\Property(property: 'successRecords', description: '成功导入数', type: 'integer')]
    private int $successRecords;

    #[OA\Property(property: 'failedRecords', description: '失败数', type: 'integer')]
    private int $failedRecords;

    #[OA\Property(property: 'errorLog', description: '错误详情（JSON数组）', type: 'string')]
    private string $errorLog;

    #[OA\Property(property: 'status', description: '状态：pending/processing/completed/failed（执行走 Laravel 队列）', type: 'string')]
    private string $status;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    #[OA\Property(property: 'completedAt', description: '完成时间', type: 'string')]
    private string $completedAt;

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
     * 获取任务名称
     */
    public function getTaskName(): string
    {
        return $this->taskName;
    }

    /**
     * 设置任务名称
     */
    public function setTaskName(string $taskName): void
    {
        $this->taskName = $taskName;
    }

    /**
     * 获取导入类型：content/user/term/attachment/comment
     */
    public function getImportType(): string
    {
        return $this->importType;
    }

    /**
     * 设置导入类型：content/user/term/attachment/comment
     */
    public function setImportType(string $importType): void
    {
        $this->importType = $importType;
    }

    /**
     * 获取来源类型：csv/excel/wordpress/json/api
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }

    /**
     * 设置来源类型：csv/excel/wordpress/json/api
     */
    public function setSourceType(string $sourceType): void
    {
        $this->sourceType = $sourceType;
    }

    /**
     * 获取源文件路径
     */
    public function getSourceFile(): string
    {
        return $this->sourceFile;
    }

    /**
     * 设置源文件路径
     */
    public function setSourceFile(string $sourceFile): void
    {
        $this->sourceFile = $sourceFile;
    }

    /**
     * 获取导入配置（字段映射、校验规则）
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * 设置导入配置（字段映射、校验规则）
     */
    public function setConfig(string $config): void
    {
        $this->config = $config;
    }

    /**
     * 获取总记录数
     */
    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    /**
     * 设置总记录数
     */
    public function setTotalRecords(int $totalRecords): void
    {
        $this->totalRecords = $totalRecords;
    }

    /**
     * 获取成功导入数
     */
    public function getSuccessRecords(): int
    {
        return $this->successRecords;
    }

    /**
     * 设置成功导入数
     */
    public function setSuccessRecords(int $successRecords): void
    {
        $this->successRecords = $successRecords;
    }

    /**
     * 获取失败数
     */
    public function getFailedRecords(): int
    {
        return $this->failedRecords;
    }

    /**
     * 设置失败数
     */
    public function setFailedRecords(int $failedRecords): void
    {
        $this->failedRecords = $failedRecords;
    }

    /**
     * 获取错误详情（JSON数组）
     */
    public function getErrorLog(): string
    {
        return $this->errorLog;
    }

    /**
     * 设置错误详情（JSON数组）
     */
    public function setErrorLog(string $errorLog): void
    {
        $this->errorLog = $errorLog;
    }

    /**
     * 获取状态：pending/processing/completed/failed（执行走 Laravel 队列）
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * 设置状态：pending/processing/completed/failed（执行走 Laravel 队列）
     */
    public function setStatus(string $status): void
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

    /**
     * 获取完成时间
     */
    public function getCompletedAt(): string
    {
        return $this->completedAt;
    }

    /**
     * 设置完成时间
     */
    public function setCompletedAt(string $completedAt): void
    {
        $this->completedAt = $completedAt;
    }
}
