<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'MessageEntity')]
class MessageEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getSenderId = 'sender_id'; // 发送者ID（NULL为系统）

    public const string getReceiverId = 'receiver_id'; // 接收者用户ID

    public const string getMsgType = 'msg_type'; // 消息类型：system/comment/mention/audit/marketing

    public const string getTitle = 'title'; // 消息标题

    public const string getContent = 'content'; // 消息内容（纯文本或HTML）

    public const string getLinkUrl = 'link_url'; // 跳转链接

    public const string getExtraData = 'extra_data'; // 扩展数据（如关联内容ID）

    public const string getIsRead = 'is_read'; // 是否已读：0未读，1已读

    public const string getReadAt = 'read_at'; // 阅读时间

    public const string getPriority = 'priority'; // 优先级：0普通，1重要，2紧急

    public const string getExpireAt = 'expire_at'; // 消息过期时间（NULL永不过期）

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'senderId', description: '发送者ID（NULL为系统）', type: 'integer')]
    private int $senderId;

    #[OA\Property(property: 'receiverId', description: '接收者用户ID', type: 'integer')]
    private int $receiverId;

    #[OA\Property(property: 'msgType', description: '消息类型：system/comment/mention/audit/marketing', type: 'string')]
    private string $msgType;

    #[OA\Property(property: 'title', description: '消息标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '消息内容（纯文本或HTML）', type: 'string')]
    private string $content;

    #[OA\Property(property: 'linkUrl', description: '跳转链接', type: 'string')]
    private string $linkUrl;

    #[OA\Property(property: 'extraData', description: '扩展数据（如关联内容ID）', type: 'string')]
    private string $extraData;

    #[OA\Property(property: 'isRead', description: '是否已读：0未读，1已读', type: 'integer')]
    private int $isRead;

    #[OA\Property(property: 'readAt', description: '阅读时间', type: 'string')]
    private string $readAt;

    #[OA\Property(property: 'priority', description: '优先级：0普通，1重要，2紧急', type: 'integer')]
    private int $priority;

    #[OA\Property(property: 'expireAt', description: '消息过期时间（NULL永不过期）', type: 'string')]
    private string $expireAt;

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
     * 获取发送者ID（NULL为系统）
     */
    public function getSenderId(): int
    {
        return $this->senderId;
    }

    /**
     * 设置发送者ID（NULL为系统）
     */
    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * 获取接收者用户ID
     */
    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    /**
     * 设置接收者用户ID
     */
    public function setReceiverId(int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    /**
     * 获取消息类型：system/comment/mention/audit/marketing
     */
    public function getMsgType(): string
    {
        return $this->msgType;
    }

    /**
     * 设置消息类型：system/comment/mention/audit/marketing
     */
    public function setMsgType(string $msgType): void
    {
        $this->msgType = $msgType;
    }

    /**
     * 获取消息标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置消息标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取消息内容（纯文本或HTML）
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置消息内容（纯文本或HTML）
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取跳转链接
     */
    public function getLinkUrl(): string
    {
        return $this->linkUrl;
    }

    /**
     * 设置跳转链接
     */
    public function setLinkUrl(string $linkUrl): void
    {
        $this->linkUrl = $linkUrl;
    }

    /**
     * 获取扩展数据（如关联内容ID）
     */
    public function getExtraData(): string
    {
        return $this->extraData;
    }

    /**
     * 设置扩展数据（如关联内容ID）
     */
    public function setExtraData(string $extraData): void
    {
        $this->extraData = $extraData;
    }

    /**
     * 获取是否已读：0未读，1已读
     */
    public function getIsRead(): int
    {
        return $this->isRead;
    }

    /**
     * 设置是否已读：0未读，1已读
     */
    public function setIsRead(int $isRead): void
    {
        $this->isRead = $isRead;
    }

    /**
     * 获取阅读时间
     */
    public function getReadAt(): string
    {
        return $this->readAt;
    }

    /**
     * 设置阅读时间
     */
    public function setReadAt(string $readAt): void
    {
        $this->readAt = $readAt;
    }

    /**
     * 获取优先级：0普通，1重要，2紧急
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * 设置优先级：0普通，1重要，2紧急
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * 获取消息过期时间（NULL永不过期）
     */
    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    /**
     * 设置消息过期时间（NULL永不过期）
     */
    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
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
