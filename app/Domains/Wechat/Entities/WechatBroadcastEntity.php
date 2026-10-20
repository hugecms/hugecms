<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WechatBroadcastEntity')]
class WechatBroadcastEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getAppid = 'appid'; // 群发公众号AppID

    public const string getType = 'type'; // 类型:news=图文,text=文本,image=图片

    public const string getContent = 'content'; // 群发内容(JSON),图文为素材列表

    public const string getTarget = 'target'; // 目标:all=全部粉丝,或标签ID列表

    public const string getSendCount = 'send_count'; // 送达数

    public const string getStatus = 'status'; // 状态:0=待发送,1=已发送,2=发送失败

    public const string getMsgId = 'msg_id'; // 微信群发消息ID,回拉发送结果用

    public const string getUserType = 'user_type'; // 操作账户类型:admin,system

    public const string getUserId = 'user_id'; // 操作人ID

    public const string getSentAt = 'sent_at'; // 发送时间

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'appid', description: '群发公众号AppID', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'type', description: '类型:news=图文,text=文本,image=图片', type: 'string')]
    private string $type;

    #[OA\Property(property: 'content', description: '群发内容(JSON),图文为素材列表', type: 'string')]
    private string $content;

    #[OA\Property(property: 'target', description: '目标:all=全部粉丝,或标签ID列表', type: 'string')]
    private string $target;

    #[OA\Property(property: 'sendCount', description: '送达数', type: 'integer')]
    private int $sendCount;

    #[OA\Property(property: 'status', description: '状态:0=待发送,1=已发送,2=发送失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'msgId', description: '微信群发消息ID,回拉发送结果用', type: 'string')]
    private string $msgId;

    #[OA\Property(property: 'userType', description: '操作账户类型:admin,system', type: 'string')]
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
     * 获取目标:all=全部粉丝,或标签ID列表
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * 设置目标:all=全部粉丝,或标签ID列表
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
     * 获取操作账户类型:admin,system
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * 设置操作账户类型:admin,system
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
