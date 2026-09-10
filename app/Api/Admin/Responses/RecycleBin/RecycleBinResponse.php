<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\RecycleBin;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RecycleBinResponse')]
class RecycleBinResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'deletedBy', description: '删除人用户ID', type: 'integer')]
    private int $deletedBy;

    #[OA\Property(property: 'targetType', description: '原对象类型：content/term/attachment/user/form_submission/comment', type: 'string')]
    private string $targetType;

    #[OA\Property(property: 'targetId', description: '原对象ID', type: 'string')]
    private string $targetId;

    #[OA\Property(property: 'originalData', description: '删除前的全量数据快照（JSON）', type: 'string')]
    private string $originalData;

    #[OA\Property(property: 'restoreData', description: '恢复时所需的数据映射（如恢复时需新建ID）', type: 'string')]
    private string $restoreData;

    #[OA\Property(property: 'retentionDays', description: '保留天数（超时由 Scheduler 物理清除）', type: 'integer')]
    private int $retentionDays;

    #[OA\Property(property: 'createdAt', description: '删除时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'expireAt', description: '过期时间（虚拟生成列）', type: 'string')]
    private string $expireAt;

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
     * 获取删除人用户ID
     */
    public function getDeletedBy(): int
    {
        return $this->deletedBy;
    }

    /**
     * 设置删除人用户ID
     */
    public function setDeletedBy(int $deletedBy): void
    {
        $this->deletedBy = $deletedBy;
    }

    /**
     * 获取原对象类型：content/term/attachment/user/form_submission/comment
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * 设置原对象类型：content/term/attachment/user/form_submission/comment
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * 获取原对象ID
     */
    public function getTargetId(): string
    {
        return $this->targetId;
    }

    /**
     * 设置原对象ID
     */
    public function setTargetId(string $targetId): void
    {
        $this->targetId = $targetId;
    }

    /**
     * 获取删除前的全量数据快照（JSON）
     */
    public function getOriginalData(): string
    {
        return $this->originalData;
    }

    /**
     * 设置删除前的全量数据快照（JSON）
     */
    public function setOriginalData(string $originalData): void
    {
        $this->originalData = $originalData;
    }

    /**
     * 获取恢复时所需的数据映射（如恢复时需新建ID）
     */
    public function getRestoreData(): string
    {
        return $this->restoreData;
    }

    /**
     * 设置恢复时所需的数据映射（如恢复时需新建ID）
     */
    public function setRestoreData(string $restoreData): void
    {
        $this->restoreData = $restoreData;
    }

    /**
     * 获取保留天数（超时由 Scheduler 物理清除）
     */
    public function getRetentionDays(): int
    {
        return $this->retentionDays;
    }

    /**
     * 设置保留天数（超时由 Scheduler 物理清除）
     */
    public function setRetentionDays(int $retentionDays): void
    {
        $this->retentionDays = $retentionDays;
    }

    /**
     * 获取删除时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置删除时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * 获取过期时间（虚拟生成列）
     */
    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    /**
     * 设置过期时间（虚拟生成列）
     */
    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
    }
}
