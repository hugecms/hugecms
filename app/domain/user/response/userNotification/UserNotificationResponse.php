<?php

declare(strict_types=1);

namespace app\domain\user\response\userNotification;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserNotificationResponse')]
class UserNotificationResponse
{
    use ArrayHelper;

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
