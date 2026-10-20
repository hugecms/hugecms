<?php

declare(strict_types=1);

namespace App\Domains\Notification\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NotificationEntity')]
class NotificationEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getType = 'type'; // 通知类型(通知类名)

    public const string getNotifiableType = 'notifiable_type'; // 通知目标类型,如:App\Models\User

    public const string getNotifiableId = 'notifiable_id'; // 通知目标ID

    public const string getData = 'data'; // 通知数据(JSON)

    public const string getReadAt = 'read_at'; // 已读时间,空=未读

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'string')]
    private string $id;

    #[OA\Property(property: 'type', description: '通知类型(通知类名)', type: 'string')]
    private string $type;

    #[OA\Property(property: 'notifiableType', description: '通知目标类型,如:App\Models\User', type: 'string')]
    private string $notifiableType;

    #[OA\Property(property: 'notifiableId', description: '通知目标ID', type: 'integer')]
    private int $notifiableId;

    #[OA\Property(property: 'data', description: '通知数据(JSON)', type: 'string')]
    private string $data;

    #[OA\Property(property: 'readAt', description: '已读时间,空=未读', type: 'string')]
    private string $readAt;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

    /**
     * 获取ID
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取通知类型(通知类名)
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置通知类型(通知类名)
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取通知目标类型,如:App\Models\User
     */
    public function getNotifiableType(): string
    {
        return $this->notifiableType;
    }

    /**
     * 设置通知目标类型,如:App\Models\User
     */
    public function setNotifiableType(string $notifiableType): void
    {
        $this->notifiableType = $notifiableType;
    }

    /**
     * 获取通知目标ID
     */
    public function getNotifiableId(): int
    {
        return $this->notifiableId;
    }

    /**
     * 设置通知目标ID
     */
    public function setNotifiableId(int $notifiableId): void
    {
        $this->notifiableId = $notifiableId;
    }

    /**
     * 获取通知数据(JSON)
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * 设置通知数据(JSON)
     */
    public function setData(string $data): void
    {
        $this->data = $data;
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
