<?php

declare(strict_types=1);

namespace App\Domains\Chat\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ChatMessageEntity')]
class ChatMessageEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getSessionId = 'session_id'; // 会话ID

    public const string getSenderType = 'sender_type'; // 发送者类型:user=用户,admin=客服,bot=机器人,system=系统

    public const string getSenderId = 'sender_id'; // 发送者ID,system为0

    public const string getType = 'type'; // 消息类型:text,image,product=商品卡片,order=订单卡片,system=系统事件

    public const string getContent = 'content'; // 消息内容,文本或卡片数据(JSON)

    public const string getCreateTime = 'create_time'; // 创建时间,消息只追加不修改

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'sessionId', description: '会话ID', type: 'integer')]
    private int $sessionId;

    #[OA\Property(property: 'senderType', description: '发送者类型:user=用户,admin=客服,bot=机器人,system=系统', type: 'string')]
    private string $senderType;

    #[OA\Property(property: 'senderId', description: '发送者ID,system为0', type: 'integer')]
    private int $senderId;

    #[OA\Property(property: 'type', description: '消息类型:text,image,product=商品卡片,order=订单卡片,system=系统事件', type: 'string')]
    private string $type;

    #[OA\Property(property: 'content', description: '消息内容,文本或卡片数据(JSON)', type: 'string')]
    private string $content;

    #[OA\Property(property: 'createTime', description: '创建时间,消息只追加不修改', type: 'string')]
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
     * 获取会话ID
     */
    public function getSessionId(): int
    {
        return $this->sessionId;
    }

    /**
     * 设置会话ID
     */
    public function setSessionId(int $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    /**
     * 获取发送者类型:user=用户,admin=客服,bot=机器人,system=系统
     */
    public function getSenderType(): string
    {
        return $this->senderType;
    }

    /**
     * 设置发送者类型:user=用户,admin=客服,bot=机器人,system=系统
     */
    public function setSenderType(string $senderType): void
    {
        $this->senderType = $senderType;
    }

    /**
     * 获取发送者ID,system为0
     */
    public function getSenderId(): int
    {
        return $this->senderId;
    }

    /**
     * 设置发送者ID,system为0
     */
    public function setSenderId(int $senderId): void
    {
        $this->senderId = $senderId;
    }

    /**
     * 获取消息类型:text,image,product=商品卡片,order=订单卡片,system=系统事件
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置消息类型:text,image,product=商品卡片,order=订单卡片,system=系统事件
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取消息内容,文本或卡片数据(JSON)
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置消息内容,文本或卡片数据(JSON)
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取创建时间,消息只追加不修改
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间,消息只追加不修改
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
