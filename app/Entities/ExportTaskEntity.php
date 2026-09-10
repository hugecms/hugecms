<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ExportTaskEntity')]
class ExportTaskEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOperatorId = 'operator_id'; // 操作人ID

    public const string getTaskName = 'task_name'; // 任务名称

    public const string getExportType = 'export_type'; // 导出类型：content/user/term/comment/statistics

    public const string getFilterConditions = 'filter_conditions'; // 筛选条件（JSON）

    public const string getExportFormat = 'export_format'; // 导出格式：csv/excel/json/xml

    public const string getFilePath = 'file_path'; // 导出文件存储路径

    public const string getFileSize = 'file_size'; // 文件大小（字节）

    public const string getTotalRecords = 'total_records'; // 导出记录数

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

    #[OA\Property(property: 'exportType', description: '导出类型：content/user/term/comment/statistics', type: 'string')]
    private string $exportType;

    #[OA\Property(property: 'filterConditions', description: '筛选条件（JSON）', type: 'string')]
    private string $filterConditions;

    #[OA\Property(property: 'exportFormat', description: '导出格式：csv/excel/json/xml', type: 'string')]
    private string $exportFormat;

    #[OA\Property(property: 'filePath', description: '导出文件存储路径', type: 'string')]
    private string $filePath;

    #[OA\Property(property: 'fileSize', description: '文件大小（字节）', type: 'integer')]
    private int $fileSize;

    #[OA\Property(property: 'totalRecords', description: '导出记录数', type: 'integer')]
    private int $totalRecords;

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
     * 获取导出类型：content/user/term/comment/statistics
     */
    public function getExportType(): string
    {
        return $this->exportType;
    }

    /**
     * 设置导出类型：content/user/term/comment/statistics
     */
    public function setExportType(string $exportType): void
    {
        $this->exportType = $exportType;
    }

    /**
     * 获取筛选条件（JSON）
     */
    public function getFilterConditions(): string
    {
        return $this->filterConditions;
    }

    /**
     * 设置筛选条件（JSON）
     */
    public function setFilterConditions(string $filterConditions): void
    {
        $this->filterConditions = $filterConditions;
    }

    /**
     * 获取导出格式：csv/excel/json/xml
     */
    public function getExportFormat(): string
    {
        return $this->exportFormat;
    }

    /**
     * 设置导出格式：csv/excel/json/xml
     */
    public function setExportFormat(string $exportFormat): void
    {
        $this->exportFormat = $exportFormat;
    }

    /**
     * 获取导出文件存储路径
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * 设置导出文件存储路径
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * 获取文件大小（字节）
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }

    /**
     * 设置文件大小（字节）
     */
    public function setFileSize(int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    /**
     * 获取导出记录数
     */
    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    /**
     * 设置导出记录数
     */
    public function setTotalRecords(int $totalRecords): void
    {
        $this->totalRecords = $totalRecords;
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
