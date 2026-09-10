<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'CommentEntity')]
class CommentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getContentId = 'content_id'; // 关联内容主表ID（全模型通用）

    public const string getUserId = 'user_id'; // 评论者用户ID（NULL表示游客）

    public const string getParentId = 'parent_id'; // 父评论ID（0=顶级评论，支持楼中楼）

    public const string getReplyToUserId = 'reply_to_user_id'; // 被回复用户ID（渲染&quot;回复@xxx&quot;用）

    public const string getAuthorName = 'author_name'; // 评论者昵称（游客填写；登录用户冗余，防销号后无记录）

    public const string getAuthorEmail = 'author_email'; // 评论者邮箱（游客填写，用于头像/回复通知）

    public const string getAuthorUrl = 'author_url'; // 评论者主页URL

    public const string getContent = 'content'; // 评论内容（纯文本，入库前过敏感词）

    public const string getIp = 'ip'; // 评论者IP（配合IP黑名单反垃圾）

    public const string getUserAgent = 'user_agent'; // 评论者UA

    public const string getStatus = 'status'; // 状态：pending待审核/approved已通过/spam垃圾/trash回收站

    public const string getLikeCount = 'like_count'; // 点赞数

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'contentId', description: '关联内容主表ID（全模型通用）', type: 'integer')]
    private int $contentId;

    #[OA\Property(property: 'userId', description: '评论者用户ID（NULL表示游客）', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'parentId', description: '父评论ID（0=顶级评论，支持楼中楼）', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'replyToUserId', description: '被回复用户ID（渲染&quot;回复@xxx&quot;用）', type: 'integer')]
    private int $replyToUserId;

    #[OA\Property(property: 'authorName', description: '评论者昵称（游客填写；登录用户冗余，防销号后无记录）', type: 'string')]
    private string $authorName;

    #[OA\Property(property: 'authorEmail', description: '评论者邮箱（游客填写，用于头像/回复通知）', type: 'string')]
    private string $authorEmail;

    #[OA\Property(property: 'authorUrl', description: '评论者主页URL', type: 'string')]
    private string $authorUrl;

    #[OA\Property(property: 'content', description: '评论内容（纯文本，入库前过敏感词）', type: 'string')]
    private string $content;

    #[OA\Property(property: 'ip', description: '评论者IP（配合IP黑名单反垃圾）', type: 'string')]
    private string $ip;

    #[OA\Property(property: 'userAgent', description: '评论者UA', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'status', description: '状态：pending待审核/approved已通过/spam垃圾/trash回收站', type: 'string')]
    private string $status;

    #[OA\Property(property: 'likeCount', description: '点赞数', type: 'integer')]
    private int $likeCount;

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
     * 获取关联内容主表ID（全模型通用）
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * 设置关联内容主表ID（全模型通用）
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * 获取评论者用户ID（NULL表示游客）
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置评论者用户ID（NULL表示游客）
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取父评论ID（0=顶级评论，支持楼中楼）
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父评论ID（0=顶级评论，支持楼中楼）
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取被回复用户ID（渲染&quot;回复@xxx&quot;用）
     */
    public function getReplyToUserId(): int
    {
        return $this->replyToUserId;
    }

    /**
     * 设置被回复用户ID（渲染&quot;回复@xxx&quot;用）
     */
    public function setReplyToUserId(int $replyToUserId): void
    {
        $this->replyToUserId = $replyToUserId;
    }

    /**
     * 获取评论者昵称（游客填写；登录用户冗余，防销号后无记录）
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * 设置评论者昵称（游客填写；登录用户冗余，防销号后无记录）
     */
    public function setAuthorName(string $authorName): void
    {
        $this->authorName = $authorName;
    }

    /**
     * 获取评论者邮箱（游客填写，用于头像/回复通知）
     */
    public function getAuthorEmail(): string
    {
        return $this->authorEmail;
    }

    /**
     * 设置评论者邮箱（游客填写，用于头像/回复通知）
     */
    public function setAuthorEmail(string $authorEmail): void
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * 获取评论者主页URL
     */
    public function getAuthorUrl(): string
    {
        return $this->authorUrl;
    }

    /**
     * 设置评论者主页URL
     */
    public function setAuthorUrl(string $authorUrl): void
    {
        $this->authorUrl = $authorUrl;
    }

    /**
     * 获取评论内容（纯文本，入库前过敏感词）
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置评论内容（纯文本，入库前过敏感词）
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取评论者IP（配合IP黑名单反垃圾）
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * 设置评论者IP（配合IP黑名单反垃圾）
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * 获取评论者UA
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置评论者UA
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * 获取状态：pending待审核/approved已通过/spam垃圾/trash回收站
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * 设置状态：pending待审核/approved已通过/spam垃圾/trash回收站
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取点赞数
     */
    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    /**
     * 设置点赞数
     */
    public function setLikeCount(int $likeCount): void
    {
        $this->likeCount = $likeCount;
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
