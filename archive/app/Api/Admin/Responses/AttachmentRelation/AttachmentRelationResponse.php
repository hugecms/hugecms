<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\AttachmentRelation;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AttachmentRelationResponse')]
class AttachmentRelationResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'attachmentId', description: '附件ID', type: 'integer')]
    private int $attachmentId;

    #[OA\Property(property: 'contentId', description: '关联的内容ID', type: 'integer')]
    private int $contentId;

    #[OA\Property(property: 'fieldKey', description: '关联到内容的哪个字段（如：封面图、详情图集）', type: 'string')]
    private string $fieldKey;

    #[OA\Property(property: 'sort', description: '在该内容下的排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
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
     * 获取附件ID
     */
    public function getAttachmentId(): int
    {
        return $this->attachmentId;
    }

    /**
     * 设置附件ID
     */
    public function setAttachmentId(int $attachmentId): void
    {
        $this->attachmentId = $attachmentId;
    }

    /**
     * 获取关联的内容ID
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * 设置关联的内容ID
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * 获取关联到内容的哪个字段（如：封面图、详情图集）
     */
    public function getFieldKey(): string
    {
        return $this->fieldKey;
    }

    /**
     * 设置关联到内容的哪个字段（如：封面图、详情图集）
     */
    public function setFieldKey(string $fieldKey): void
    {
        $this->fieldKey = $fieldKey;
    }

    /**
     * 获取在该内容下的排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置在该内容下的排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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
}
