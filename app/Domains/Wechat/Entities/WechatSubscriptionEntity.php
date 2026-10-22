<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WechatSubscriptionEntity')]
class WechatSubscriptionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getAppid = 'appid'; // 授权所在小程序AppID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getTemplateId = 'template_id'; // 模板ID,关联wechat_templates

    public const string getTimes = 'times'; // 累计授权次数

    public const string getRemain = 'remain'; // 剩余可发次数,发送成功后扣减

    public const string getLastSubscribedAt = 'last_subscribed_at'; // 最近授权时间

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'appid', description: '授权所在小程序AppID', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'templateId', description: '模板ID,关联wechat_templates', type: 'integer')]
    private int $templateId;

    #[OA\Property(property: 'times', description: '累计授权次数', type: 'integer')]
    private int $times;

    #[OA\Property(property: 'remain', description: '剩余可发次数,发送成功后扣减', type: 'integer')]
    private int $remain;

    #[OA\Property(property: 'lastSubscribedAt', description: '最近授权时间', type: 'string')]
    private string $lastSubscribedAt;

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
     * 获取授权所在小程序AppID
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置授权所在小程序AppID
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * 获取模板ID,关联wechat_templates
     */
    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    /**
     * 设置模板ID,关联wechat_templates
     */
    public function setTemplateId(int $templateId): void
    {
        $this->templateId = $templateId;
    }

    /**
     * 获取累计授权次数
     */
    public function getTimes(): int
    {
        return $this->times;
    }

    /**
     * 设置累计授权次数
     */
    public function setTimes(int $times): void
    {
        $this->times = $times;
    }

    /**
     * 获取剩余可发次数,发送成功后扣减
     */
    public function getRemain(): int
    {
        return $this->remain;
    }

    /**
     * 设置剩余可发次数,发送成功后扣减
     */
    public function setRemain(int $remain): void
    {
        $this->remain = $remain;
    }

    /**
     * 获取最近授权时间
     */
    public function getLastSubscribedAt(): string
    {
        return $this->lastSubscribedAt;
    }

    /**
     * 设置最近授权时间
     */
    public function setLastSubscribedAt(string $lastSubscribedAt): void
    {
        $this->lastSubscribedAt = $lastSubscribedAt;
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
