<?php

declare(strict_types=1);

namespace App\Domains\Content\Responses\ContentArticle;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ContentArticleResponse')]
class ContentArticleResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'categoryId', description: '栏目ID', type: 'integer')]
    private int $categoryId;

    #[OA\Property(property: 'title', description: '标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'slug', description: '文章标识,用于URL', type: 'string')]
    private string $slug;

    #[OA\Property(property: 'summary', description: '摘要', type: 'string')]
    private string $summary;

    #[OA\Property(property: 'cover', description: '封面图', type: 'string')]
    private string $cover;

    #[OA\Property(property: 'authorId', description: '作者管理员ID', type: 'integer')]
    private int $authorId;

    #[OA\Property(property: 'author', description: '作者名称快照', type: 'string')]
    private string $author;

    #[OA\Property(property: 'isTop', description: '置顶:0=否,1=是', type: 'integer')]
    private int $isTop;

    #[OA\Property(property: 'isRecommend', description: '推荐:0=否,1=是', type: 'integer')]
    private int $isRecommend;

    #[OA\Property(property: 'status', description: '状态:0=草稿,1=发布,2=下架', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'views', description: '浏览量', type: 'integer')]
    private int $views;

    #[OA\Property(property: 'publishedAt', description: '发布时间', type: 'string')]
    private string $publishedAt;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

    #[OA\Property(property: 'deleteTime', description: '', type: 'string')]
    private string $deleteTime;

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
     * 获取栏目ID
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * 设置栏目ID
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * 获取标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取文章标识,用于URL
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * 设置文章标识,用于URL
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * 获取摘要
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * 设置摘要
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * 获取封面图
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * 设置封面图
     */
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }

    /**
     * 获取作者管理员ID
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * 设置作者管理员ID
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * 获取作者名称快照
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * 设置作者名称快照
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * 获取置顶:0=否,1=是
     */
    public function getIsTop(): int
    {
        return $this->isTop;
    }

    /**
     * 设置置顶:0=否,1=是
     */
    public function setIsTop(int $isTop): void
    {
        $this->isTop = $isTop;
    }

    /**
     * 获取推荐:0=否,1=是
     */
    public function getIsRecommend(): int
    {
        return $this->isRecommend;
    }

    /**
     * 设置推荐:0=否,1=是
     */
    public function setIsRecommend(int $isRecommend): void
    {
        $this->isRecommend = $isRecommend;
    }

    /**
     * 获取状态:0=草稿,1=发布,2=下架
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=草稿,1=发布,2=下架
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取浏览量
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * 设置浏览量
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * 获取发布时间
     */
    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    /**
     * 设置发布时间
     */
    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
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

    /**
     * 获取
     */
    public function getDeleteTime(): string
    {
        return $this->deleteTime;
    }

    /**
     * 设置
     */
    public function setDeleteTime(string $deleteTime): void
    {
        $this->deleteTime = $deleteTime;
    }
}
