<?php

declare(strict_types=1);

namespace App\Domains\Content\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentArticleContentEntity')]
class ContentArticleContentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getArticleId = 'article_id'; // 文章ID

    public const string getContent = 'content'; // 正文(富文本)

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    #[OA\Property(property: 'articleId', description: '文章ID', type: 'integer')]
    private int $articleId;

    #[OA\Property(property: 'content', description: '正文(富文本)', type: 'string')]
    private string $content;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

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
     * 获取正文(富文本)
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置正文(富文本)
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
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

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
