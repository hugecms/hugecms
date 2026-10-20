<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Responses\WechatFan;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'WechatFanResponse')]
class WechatFanResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'appid', description: '公众号AppID', type: 'string')]
    private string $appid;

    #[OA\Property(property: 'openid', description: '公众号OpenID', type: 'string')]
    private string $openid;

    #[OA\Property(property: 'unionid', description: '开放平台UnionID,跨端识别同一用户', type: 'string')]
    private string $unionid;

    #[OA\Property(property: 'nickname', description: '昵称快照', type: 'string')]
    private string $nickname;

    #[OA\Property(property: 'avatar', description: '头像快照', type: 'string')]
    private string $avatar;

    #[OA\Property(property: 'subscribed', description: '关注状态:0=已取关,1=已关注', type: 'integer')]
    private int $subscribed;

    #[OA\Property(property: 'subscribedAt', description: '关注时间', type: 'string')]
    private string $subscribedAt;

    #[OA\Property(property: 'unsubscribedAt', description: '取关时间', type: 'string')]
    private string $unsubscribedAt;

    #[OA\Property(property: 'subscribeScene', description: '关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码', type: 'string')]
    private string $subscribeScene;

    #[OA\Property(property: 'tags', description: '标签ID列表(JSON),群发圈人依据', type: 'string')]
    private string $tags;

    #[OA\Property(property: 'userId', description: '关联注册用户ID,未注册为空', type: 'integer')]
    private int $userId;

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
     * 获取公众号AppID
     */
    public function getAppid(): string
    {
        return $this->appid;
    }

    /**
     * 设置公众号AppID
     */
    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    /**
     * 获取公众号OpenID
     */
    public function getOpenid(): string
    {
        return $this->openid;
    }

    /**
     * 设置公众号OpenID
     */
    public function setOpenid(string $openid): void
    {
        $this->openid = $openid;
    }

    /**
     * 获取开放平台UnionID,跨端识别同一用户
     */
    public function getUnionid(): string
    {
        return $this->unionid;
    }

    /**
     * 设置开放平台UnionID,跨端识别同一用户
     */
    public function setUnionid(string $unionid): void
    {
        $this->unionid = $unionid;
    }

    /**
     * 获取昵称快照
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * 设置昵称快照
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * 获取头像快照
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * 设置头像快照
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * 获取关注状态:0=已取关,1=已关注
     */
    public function getSubscribed(): int
    {
        return $this->subscribed;
    }

    /**
     * 设置关注状态:0=已取关,1=已关注
     */
    public function setSubscribed(int $subscribed): void
    {
        $this->subscribed = $subscribed;
    }

    /**
     * 获取关注时间
     */
    public function getSubscribedAt(): string
    {
        return $this->subscribedAt;
    }

    /**
     * 设置关注时间
     */
    public function setSubscribedAt(string $subscribedAt): void
    {
        $this->subscribedAt = $subscribedAt;
    }

    /**
     * 获取取关时间
     */
    public function getUnsubscribedAt(): string
    {
        return $this->unsubscribedAt;
    }

    /**
     * 设置取关时间
     */
    public function setUnsubscribedAt(string $unsubscribedAt): void
    {
        $this->unsubscribedAt = $unsubscribedAt;
    }

    /**
     * 获取关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码
     */
    public function getSubscribeScene(): string
    {
        return $this->subscribeScene;
    }

    /**
     * 设置关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码
     */
    public function setSubscribeScene(string $subscribeScene): void
    {
        $this->subscribeScene = $subscribeScene;
    }

    /**
     * 获取标签ID列表(JSON),群发圈人依据
     */
    public function getTags(): string
    {
        return $this->tags;
    }

    /**
     * 设置标签ID列表(JSON),群发圈人依据
     */
    public function setTags(string $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * 获取关联注册用户ID,未注册为空
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置关联注册用户ID,未注册为空
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
