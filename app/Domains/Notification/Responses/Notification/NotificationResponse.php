<?php

declare(strict_types=1);

namespace App\Domains\Notification\Responses\Notification;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NotificationResponse')]
class NotificationResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'string')]
    private string $id;

    #[OA\Property(property: 'type', description: '', type: 'string')]
    private string $type;

    #[OA\Property(property: 'notifiableType', description: '', type: 'string')]
    private string $notifiableType;

    #[OA\Property(property: 'notifiableId', description: '', type: 'integer')]
    private int $notifiableId;

    #[OA\Property(property: 'data', description: '', type: 'string')]
    private string $data;

    #[OA\Property(property: 'readAt', description: '', type: 'string')]
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
     * 获取
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取
     */
    public function getNotifiableType(): string
    {
        return $this->notifiableType;
    }

    /**
     * 设置
     */
    public function setNotifiableType(string $notifiableType): void
    {
        $this->notifiableType = $notifiableType;
    }

    /**
     * 获取
     */
    public function getNotifiableId(): int
    {
        return $this->notifiableId;
    }

    /**
     * 设置
     */
    public function setNotifiableId(int $notifiableId): void
    {
        $this->notifiableId = $notifiableId;
    }

    /**
     * 获取
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * 设置
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * 获取
     */
    public function getReadAt(): string
    {
        return $this->readAt;
    }

    /**
     * 设置
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
