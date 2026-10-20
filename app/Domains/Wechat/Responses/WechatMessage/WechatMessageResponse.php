<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Responses\WechatMessage;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WechatMessageResponse')]
class WechatMessageResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'appid', description: '发送所用应用AppID', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'userId', description: '目标用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'templateId', description: '模板ID', type: 'integer')]
    private int $templateId;

    #[OA\Property(property: 'scene', description: '业务场景,同模板表scene', type: 'string')]
    private string $scene;

    #[OA\Property(property: 'relatedId', description: '关联业务ID,如订单ID', type: 'integer')]
    private int $relatedId;

    #[OA\Property(property: 'openid', description: '接收者OpenID快照', type: 'string')]
    private string $openid;

    #[OA\Property(property: 'content', description: '推送内容快照(JSON)', type: 'string')]
    private string $content;

    #[OA\Property(property: 'status', description: '状态:0=待发送,1=成功,2=失败', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'error', description: '失败原因,如:用户未关注/订阅次数用尽', type: 'string')]
    private string $error;

    #[OA\Property(property: 'sentAt', description: '发送时间', type: 'string')]
    private string $sentAt;

    #[OA\Property(property: 'createTime', description: '创建时间', type: 'string')]
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
     * 获取发送所用应用AppID
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置发送所用应用AppID
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取目标用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置目标用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取模板ID
     */
    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    /**
     * 设置模板ID
     */
    public function setTemplateId(int $templateId): void
    {
        $this->templateId = $templateId;
    }

    /**
     * 获取业务场景,同模板表scene
     */
    public function getScene(): string
    {
        return $this->scene;
    }

    /**
     * 设置业务场景,同模板表scene
     */
    public function setScene(string $scene): void
    {
        $this->scene = $scene;
    }

    /**
     * 获取关联业务ID,如订单ID
     */
    public function getRelatedId(): int
    {
        return $this->relatedId;
    }

    /**
     * 设置关联业务ID,如订单ID
     */
    public function setRelatedId(int $relatedId): void
    {
        $this->relatedId = $relatedId;
    }

    /**
     * 获取接收者OpenID快照
     */
    public function getOpenid(): string
    {
        return $this->openid;
    }

    /**
     * 设置接收者OpenID快照
     */
    public function setOpenid(string $openid): void
    {
        $this->openid = $openid;
    }

    /**
     * 获取推送内容快照(JSON)
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置推送内容快照(JSON)
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取状态:0=待发送,1=成功,2=失败
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=待发送,1=成功,2=失败
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取失败原因,如:用户未关注/订阅次数用尽
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * 设置失败原因,如:用户未关注/订阅次数用尽
     */
    public function setError(string $error): void
    {
        $this->error = $error;
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
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置创建时间
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
