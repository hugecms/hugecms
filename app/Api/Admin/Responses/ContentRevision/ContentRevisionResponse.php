<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\ContentRevision;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentRevisionResponse')]
class ContentRevisionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contentId', description: '内容主表ID', type: 'integer')]
    private int $contentId;

    #[OA\Property(property: 'authorId', description: '修改人', type: 'integer')]
    private int $authorId;

    #[OA\Property(property: 'revisionData', description: '修改时的全量数据快照（JSON）', type: 'string')]
    private string $revisionData;

    #[OA\Property(property: 'remark', description: '修改备注', type: 'string')]
    private string $remark;

    #[OA\Property(property: 'createdAt', description: '修订时间', type: 'string')]
    private string $createdAt;

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
     * 获取内容主表ID
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * 设置内容主表ID
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * 获取修改人
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * 设置修改人
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * 获取修改时的全量数据快照（JSON）
     */
    public function getRevisionData(): string
    {
        return $this->revisionData;
    }

    /**
     * 设置修改时的全量数据快照（JSON）
     */
    public function setRevisionData(string $revisionData): void
    {
        $this->revisionData = $revisionData;
    }

    /**
     * 获取修改备注
     */
    public function getRemark(): string
    {
        return $this->remark;
    }

    /**
     * 设置修改备注
     */
    public function setRemark(string $remark): void
    {
        $this->remark = $remark;
    }

    /**
     * 获取修订时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置修订时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
