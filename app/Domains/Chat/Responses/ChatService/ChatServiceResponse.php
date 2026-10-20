<?php

declare(strict_types=1);

namespace App\Domains\Chat\Responses\ChatService;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ChatServiceResponse')]
class ChatServiceResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'adminUserId', description: '客服管理员ID,关联admin_users', type: 'integer')]
    private int $adminUserId;

    #[OA\Property(property: 'onlineStatus', description: '在线状态:0=离线,1=在线,2=忙碌', type: 'integer')]
    private int $onlineStatus;

    #[OA\Property(property: 'maxSessions', description: '最大接待会话数', type: 'integer')]
    private int $maxSessions;

    #[OA\Property(property: 'currentSessions', description: '当前接待数,分配依据,冗余计数', type: 'integer')]
    private int $currentSessions;

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
     * 获取客服管理员ID,关联admin_users
     */
    public function getAdminUserId(): int
    {
        return $this->adminUserId;
    }

    /**
     * 设置客服管理员ID,关联admin_users
     */
    public function setAdminUserId(int $adminUserId): void
    {
        $this->adminUserId = $adminUserId;
    }

    /**
     * 获取在线状态:0=离线,1=在线,2=忙碌
     */
    public function getOnlineStatus(): int
    {
        return $this->onlineStatus;
    }

    /**
     * 设置在线状态:0=离线,1=在线,2=忙碌
     */
    public function setOnlineStatus(int $onlineStatus): void
    {
        $this->onlineStatus = $onlineStatus;
    }

    /**
     * 获取最大接待会话数
     */
    public function getMaxSessions(): int
    {
        return $this->maxSessions;
    }

    /**
     * 设置最大接待会话数
     */
    public function setMaxSessions(int $maxSessions): void
    {
        $this->maxSessions = $maxSessions;
    }

    /**
     * 获取当前接待数,分配依据,冗余计数
     */
    public function getCurrentSessions(): int
    {
        return $this->currentSessions;
    }

    /**
     * 设置当前接待数,分配依据,冗余计数
     */
    public function setCurrentSessions(int $currentSessions): void
    {
        $this->currentSessions = $currentSessions;
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
