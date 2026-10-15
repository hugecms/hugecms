<?php

declare(strict_types=1);

namespace app\domain\user\entity;

use Juling\Foundation\Support\ArrayHelper;

class UserNotificationEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 接收用户ID

    public const string getType = 'type'; // 类型:order,comment,system

    public const string getTitle = 'title'; // 通知标题

    public const string getContent = 'content'; // 通知内容

    public const string getParams = 'params'; // 跳转参数(JSON)

    public const string getReadAt = 'read_at'; // 已读时间,空=未读

    public const string getCreateTime = 'create_time'; //

    private int $id;

    private int $userId;

    private string $type;

    private string $title;

    private string $content;

    private string $params;

    private string $readAt;

    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getParams(): string
    {
        return $this->params;
    }

    public function setParams(string $params): void
    {
        $this->params = $params;
    }

    public function getReadAt(): string
    {
        return $this->readAt;
    }

    public function setReadAt(string $readAt): void
    {
        $this->readAt = $readAt;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
