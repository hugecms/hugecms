<?php

declare(strict_types=1);

namespace App\Domains\User\Responses\UserSocial;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSocialResponse')]
class UserSocialResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'platform', description: '平台:wechat,qq,weibo,github', type: 'string')]
    private string $platform;

    #[OA\Property(property: 'openId', description: '第三方OpenID', type: 'string')]
    private string $openId;

    #[OA\Property(property: 'unionId', description: '第三方UnionID,如微信多应用互通', type: 'string')]
    private string $unionId;

    #[OA\Property(property: 'nickname', description: '第三方昵称快照', type: 'string')]
    private string $nickname;

    #[OA\Property(property: 'avatar', description: '第三方头像快照', type: 'string')]
    private string $avatar;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

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
     * 获取平台:wechat,qq,weibo,github
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * 设置平台:wechat,qq,weibo,github
     */
    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * 获取第三方OpenID
     */
    public function getOpenId(): string
    {
        return $this->openId;
    }

    /**
     * 设置第三方OpenID
     */
    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    /**
     * 获取第三方UnionID,如微信多应用互通
     */
    public function getUnionId(): string
    {
        return $this->unionId;
    }

    /**
     * 设置第三方UnionID,如微信多应用互通
     */
    public function setUnionId(string $unionId): void
    {
        $this->unionId = $unionId;
    }

    /**
     * 获取第三方昵称快照
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * 设置第三方昵称快照
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * 获取第三方头像快照
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * 设置第三方头像快照
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
