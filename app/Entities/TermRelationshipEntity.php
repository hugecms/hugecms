<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'TermRelationshipEntity')]
class TermRelationshipEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getContentId = 'content_id'; // 内容主表ID

    public const string getTermId = 'term_id'; // 分类项ID

    public const string getSort = 'sort'; // 该内容在此分类下的自定义排序

    public const string getCreatedAt = 'created_at'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contentId', description: '内容主表ID', type: 'integer')]
    private int $contentId;

    #[OA\Property(property: 'termId', description: '分类项ID', type: 'integer')]
    private int $termId;

    #[OA\Property(property: 'sort', description: '该内容在此分类下的自定义排序', type: 'integer')]
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
     * 获取分类项ID
     */
    public function getTermId(): int
    {
        return $this->termId;
    }

    /**
     * 设置分类项ID
     */
    public function setTermId(int $termId): void
    {
        $this->termId = $termId;
    }

    /**
     * 获取该内容在此分类下的自定义排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置该内容在此分类下的自定义排序
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
