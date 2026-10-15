<?php

declare(strict_types=1);

namespace app\domain\content\entity;

use Juling\Foundation\Support\ArrayHelper;

class ContentArticleEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getCategoryId = 'category_id'; // 栏目ID

    public const string getTitle = 'title'; // 标题

    public const string getSlug = 'slug'; // 文章标识,用于URL

    public const string getSummary = 'summary'; // 摘要

    public const string getCover = 'cover'; // 封面图

    public const string getAuthorId = 'author_id'; // 作者管理员ID

    public const string getAuthor = 'author'; // 作者名称快照

    public const string getIsTop = 'is_top'; // 置顶:0=否,1=是

    public const string getIsRecommend = 'is_recommend'; // 推荐:0=否,1=是

    public const string getStatus = 'status'; // 状态:0=草稿,1=发布,2=下架

    public const string getViews = 'views'; // 浏览量

    public const string getPublishedAt = 'published_at'; // 发布时间

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    public const string getDeleteTime = 'delete_time'; // 

    private int $id;

    private int $categoryId;

    private string $title;

    private string $slug;

    private string $summary;

    private string $cover;

    private int $authorId;

    private string $author;

    private int $isTop;

    private int $isRecommend;

    private int $status;

    private int $views;

    private string $publishedAt;

    private string $createTime;

    private string $updateTime;

    private string $deleteTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    public function getCover(): string
    {
        return $this->cover;
    }

    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getIsTop(): int
    {
        return $this->isTop;
    }

    public function setIsTop(int $isTop): void
    {
        $this->isTop = $isTop;
    }

    public function getIsRecommend(): int
    {
        return $this->isRecommend;
    }

    public function setIsRecommend(int $isRecommend): void
    {
        $this->isRecommend = $isRecommend;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
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

    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
