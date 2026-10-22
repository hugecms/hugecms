<?php

declare(strict_types=1);

namespace App\Domains\Content\Responses\ContentTopicItem;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentTopicItemResponse')]
class ContentTopicItemResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'topicId', description: '专题ID', type: 'integer')]
    private int $topicId;

    #[OA\Property(property: 'targetType', description: '目标类型:article=文章,product=商品', type: 'string')]
    private string $targetType;

    #[OA\Property(property: 'targetId', description: '目标ID', type: 'integer')]
    private int $targetId;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

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
     * 获取专题ID
     */
    public function getTopicId(): int
    {
        return $this->topicId;
    }

    /**
     * 设置专题ID
     */
    public function setTopicId(int $topicId): void
    {
        $this->topicId = $topicId;
    }

    /**
     * 获取目标类型:article=文章,product=商品
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * 设置目标类型:article=文章,product=商品
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * 获取目标ID
     */
    public function getTargetId(): int
    {
        return $this->targetId;
    }

    /**
     * 设置目标ID
     */
    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
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
