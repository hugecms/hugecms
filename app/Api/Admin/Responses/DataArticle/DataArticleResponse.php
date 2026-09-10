<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\DataArticle;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'DataArticleResponse')]
class DataArticleResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contentId', description: '关联内容主表ID（一对一）', type: 'integer')]
    private int $contentId;

    #[OA\Property(property: 'field1', description: '文章摘要（text）', type: 'string')]
    private string $field1;

    #[OA\Property(property: 'field2', description: '正文内容（rich_text）', type: 'string')]
    private string $field2;

    #[OA\Property(property: 'field3', description: '封面图，存附件ID或URL（image）', type: 'string')]
    private string $field3;

    #[OA\Property(property: 'extra', description: '预留JSON扩展字段（未建模数据兜底）', type: 'string')]
    private string $extra;

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
     * 获取关联内容主表ID（一对一）
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * 设置关联内容主表ID（一对一）
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * 获取文章摘要（text）
     */
    public function getField1(): string
    {
        return $this->field1;
    }

    /**
     * 设置文章摘要（text）
     */
    public function setField1(string $field1): void
    {
        $this->field1 = $field1;
    }

    /**
     * 获取正文内容（rich_text）
     */
    public function getField2(): string
    {
        return $this->field2;
    }

    /**
     * 设置正文内容（rich_text）
     */
    public function setField2(string $field2): void
    {
        $this->field2 = $field2;
    }

    /**
     * 获取封面图，存附件ID或URL（image）
     */
    public function getField3(): string
    {
        return $this->field3;
    }

    /**
     * 设置封面图，存附件ID或URL（image）
     */
    public function setField3(string $field3): void
    {
        $this->field3 = $field3;
    }

    /**
     * 获取预留JSON扩展字段（未建模数据兜底）
     */
    public function getExtra(): string
    {
        return $this->extra;
    }

    /**
     * 设置预留JSON扩展字段（未建模数据兜底）
     */
    public function setExtra(string $extra): void
    {
        $this->extra = $extra;
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
