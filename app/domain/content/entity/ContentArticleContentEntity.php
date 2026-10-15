<?php

declare(strict_types=1);

namespace app\domain\content\entity;

use Juling\Foundation\Support\ArrayHelper;

class ContentArticleContentEntity
{
    use ArrayHelper;

    public const string getArticleId = 'article_id'; // 文章ID

    public const string getContent = 'content'; // 正文(富文本)

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $articleId;

    private string $content;

    private string $createTime;

    private string $updateTime;

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
