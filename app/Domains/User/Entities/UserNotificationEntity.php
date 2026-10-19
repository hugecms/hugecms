<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserNotificationEntity')]
class UserNotificationEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 接收用户ID

    public const string getType = 'type'; // 类型:order,comment,system

    public const string getTitle = 'title'; // 通知标题

    public const string getContent = 'content'; // 通知内容

    public const string getParams = 'params'; // 跳转参数(JSON)

    public const string getReadAt = 'read_at'; // 已读时间,空=未读

    public const string getCreateTime = 'create_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '接收用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'type', description: '类型:order,comment,system', type: 'string')]
    private string $type;

    #[OA\Property(property: 'title', description: '通知标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'content', description: '通知内容', type: 'string')]
    private string $content;

    #[OA\Property(property: 'params', description: '跳转参数(JSON)', type: 'string')]
    private string $params;

    #[OA\Property(property: 'readAt', description: '已读时间,空=未读', type: 'string')]
    private string $readAt;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

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
     * 获取接收用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置接收用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取类型:order,comment,system
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:order,comment,system
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取通知标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置通知标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取通知内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置通知内容
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取跳转参数(JSON)
     */
    public function getParams(): string
    {
        return $this->params;
    }

    /**
     * 设置跳转参数(JSON)
     */
    public function setParams(string $params): void
    {
        $this->params = $params;
    }

    /**
     * 获取已读时间,空=未读
     */
    public function getReadAt(): string
    {
        return $this->readAt;
    }

    /**
     * 设置已读时间,空=未读
     */
    public function setReadAt(string $readAt): void
    {
        $this->readAt = $readAt;
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
