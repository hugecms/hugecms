<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Responses\WechatBroadcast;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WechatBroadcastResponse')]
class WechatBroadcastResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'appid', description: '群发公众号AppID', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'type', description: '类型:news=图文,text=文本,image=图片', type: 'string')]
    private string $type;

    #[OA\Property(property: 'content', description: '群发内容(JSON),图文为素材列表', type: 'string')]
    private string $content;

    #[OA\Property(property: 'target', description: '目标:all=全部粉丝;或标签ID列表', type: 'string')]
    private string $target;

    #[OA\Property(property: 'sendCount', description: '送达数', type: 'integer')]
    private int $sendCount;

    #[OA\Property(property: 'status', description: '状态:0=待发送,1=已发送,2=发送失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'msgId', description: '微信群发消息ID,回拉发送结果用', type: 'string')]
    private string $msgId;

    #[OA\Property(property: 'userType', description: '操作账户类型:admin=后台,system=系统', type: 'string')]
    private string $userType;

    #[OA\Property(property: 'userId', description: '操作人ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'sentAt', description: '发送时间', type: 'string')]
    private string $sentAt;

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
     * 获取群发公众号AppID
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置群发公众号AppID
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取类型:news=图文,text=文本,image=图片
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:news=图文,text=文本,image=图片
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取群发内容(JSON),图文为素材列表
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置群发内容(JSON),图文为素材列表
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取目标:all=全部粉丝;或标签ID列表
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * 设置目标:all=全部粉丝;或标签ID列表
     */
    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    /**
     * 获取送达数
     */
    public function getSendCount(): int
    {
        return $this->sendCount;
    }

    /**
     * 设置送达数
     */
    public function setSendCount(int $sendCount): void
    {
        $this->sendCount = $sendCount;
    }

    /**
     * 获取状态:0=待发送,1=已发送,2=发送失败
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待发送,1=已发送,2=发送失败
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取微信群发消息ID,回拉发送结果用
     */
    public function getMsgId(): string
    {
        return $this->msgId;
    }

    /**
     * 设置微信群发消息ID,回拉发送结果用
     */
    public function setMsgId(string $msgId): void
    {
        $this->msgId = $msgId;
    }

    /**
     * 获取操作账户类型:admin=后台,system=系统
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * 设置操作账户类型:admin=后台,system=系统
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * 获取操作人ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置操作人ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取发送时间
     */
    public function getSentAt(): string
    {
        return $this->sentAt;
    }

    /**
     * 设置发送时间
     */
    public function setSentAt(string $sentAt): void
    {
        $this->sentAt = $sentAt;
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
