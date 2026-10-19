<?php

declare(strict_types=1);

namespace App\Domains\Content\Responses\ContentArticleTag;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentArticleTagResponse')]
class ContentArticleTagResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'articleId', description: '文章ID', type: 'integer')]
    private int $articleId;

    #[OA\Property(property: 'tagId', description: '标签ID', type: 'integer')]
    private int $tagId;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
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
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
