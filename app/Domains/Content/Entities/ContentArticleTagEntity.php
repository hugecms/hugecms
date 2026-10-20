<?php

declare(strict_types=1);

namespace App\Domains\Content\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentArticleTagEntity')]
class ContentArticleTagEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getArticleId = 'article_id'; // 文章ID

    public const string getTagId = 'tag_id'; // 标签ID

    public const string getCreateTime = 'create_time'; // 创建时间

    #[OA\Property(property: 'articleId', description: '文章ID', type: 'integer')]
    private int $articleId;

    #[OA\Property(property: 'tagId', description: '标签ID', type: 'integer')]
    private int $tagId;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
    private string $createTime;

    /**
     * 获取文章ID
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * 设置文章ID
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * 获取标签ID
     */
    public function getTagId(): int
    {
        return $this->tagId;
    }

    /**
     * 设置标签ID
     */
    public function setTagId(int $tagId): void
    {
        $this->tagId = $tagId;
    }

    /**
     * 获取创建时间
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
