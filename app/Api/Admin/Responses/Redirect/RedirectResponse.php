<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\Redirect;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RedirectResponse')]
class RedirectResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'sourcePath', description: '来源路径（站内相对路径，以 / 开头）', type: 'string')]
    private string $sourcePath;

    #[OA\Property(property: 'targetPath', description: '目标路径（相对路径或完整URL）', type: 'string')]
    private string $targetPath;

    #[OA\Property(property: 'statusCode', description: 'HTTP状态码：301永久重定向，302临时重定向', type: 'integer')]
    private int $statusCode;

    #[OA\Property(property: 'hits', description: '命中次数（冗余计数，事务内维护）', type: 'integer')]
    private int $hits;

    #[OA\Property(property: 'lastHitAt', description: '最后命中时间', type: 'string')]
    private string $lastHitAt;

    #[OA\Property(property: 'remark', description: '备注（如：slug 改版、栏目迁移）', type: 'string')]
    private string $remark;

    #[OA\Property(property: 'status', description: '状态：0停用，1启用', type: 'integer')]
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
     * 获取来源路径（站内相对路径，以 / 开头）
     */
    public function getSourcePath(): string
    {
        return $this->sourcePath;
    }

    /**
     * 设置来源路径（站内相对路径，以 / 开头）
     */
    public function setSourcePath(string $sourcePath): void
    {
        $this->sourcePath = $sourcePath;
    }

    /**
     * 获取目标路径（相对路径或完整URL）
     */
    public function getTargetPath(): string
    {
        return $this->targetPath;
    }

    /**
     * 设置目标路径（相对路径或完整URL）
     */
    public function setTargetPath(string $targetPath): void
    {
        $this->targetPath = $targetPath;
    }

    /**
     * 获取HTTP状态码：301永久重定向，302临时重定向
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * 设置HTTP状态码：301永久重定向，302临时重定向
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * 获取命中次数（冗余计数，事务内维护）
     */
    public function getHits(): int
    {
        return $this->hits;
    }

    /**
     * 设置命中次数（冗余计数，事务内维护）
     */
    public function setHits(int $hits): void
    {
        $this->hits = $hits;
    }

    /**
     * 获取最后命中时间
     */
    public function getLastHitAt(): string
    {
        return $this->lastHitAt;
    }

    /**
     * 设置最后命中时间
     */
    public function setLastHitAt(string $lastHitAt): void
    {
        $this->lastHitAt = $lastHitAt;
    }

    /**
     * 获取备注（如：slug 改版、栏目迁移）
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置备注（如：slug 改版、栏目迁移）
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * 获取状态：0停用，1启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0停用，1启用
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
