<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SeoMetaEntity')]
class SeoMetaEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getTargetType = 'target_type'; // 目标类型：content/term/custom_page

    public const string getTargetId = 'target_id'; // 对应的目标实体ID

    public const string getTitle = 'title'; // SEO标题（浏览器Tab显示）

    public const string getKeywords = 'keywords'; // SEO关键词（逗号分隔）

    public const string getDescription = 'description'; // SEO描述（搜索结果展示）

    public const string getCanonicalUrl = 'canonical_url'; // 权威链接（防止重复页）

    public const string getRobots = 'robots'; // 机器人抓取策略

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'targetType', description: '目标类型：content/term/custom_page', type: 'string')]
    private string $targetType;

    #[OA\Property(property: 'targetId', description: '对应的目标实体ID', type: 'integer')]
    private int $targetId;

    #[OA\Property(property: 'title', description: 'SEO标题（浏览器Tab显示）', type: 'string')]
    private string $title;

    #[OA\Property(property: 'keywords', description: 'SEO关键词（逗号分隔）', type: 'string')]
    private string $keywords;

    #[OA\Property(property: 'description', description: 'SEO描述（搜索结果展示）', type: 'string')]
    private string $description;

    #[OA\Property(property: 'canonicalUrl', description: '权威链接（防止重复页）', type: 'string')]
    private string $canonicalUrl;

    #[OA\Property(property: 'robots', description: '机器人抓取策略', type: 'string')]
    private string $robots;

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
     * 获取目标类型：content/term/custom_page
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * 设置目标类型：content/term/custom_page
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * 获取对应的目标实体ID
     */
    public function getTargetId(): int
    {
        return $this->targetId;
    }

    /**
     * 设置对应的目标实体ID
     */
    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
    }

    /**
     * 获取SEO标题（浏览器Tab显示）
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置SEO标题（浏览器Tab显示）
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取SEO关键词（逗号分隔）
     */
    public function getKeywords(): string
    {
        return $this->keywords;
    }

    /**
     * 设置SEO关键词（逗号分隔）
     */
    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * 获取SEO描述（搜索结果展示）
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置SEO描述（搜索结果展示）
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取权威链接（防止重复页）
     */
    public function getCanonicalUrl(): string
    {
        return $this->canonicalUrl;
    }

    /**
     * 设置权威链接（防止重复页）
     */
    public function setCanonicalUrl(string $canonicalUrl): void
    {
        $this->canonicalUrl = $canonicalUrl;
    }

    /**
     * 获取机器人抓取策略
     */
    public function getRobots(): string
    {
        return $this->robots;
    }

    /**
     * 设置机器人抓取策略
     */
    public function setRobots(string $robots): void
    {
        $this->robots = $robots;
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
