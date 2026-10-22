<?php

declare(strict_types=1);

namespace App\Domains\User\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSocialEntity')]
class UserSocialEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getPlatform = 'platform'; // 平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub

    public const string getOpenId = 'open_id'; // 第三方OpenID

    public const string getUnionId = 'union_id'; // 第三方UnionID,如微信多应用互通

    public const string getNickname = 'nickname'; // 第三方昵称快照

    public const string getAvatar = 'avatar'; // 第三方头像快照

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'platform', description: '平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub', type: 'string')]
    private string $platform;

    #[OA\Property(property: 'openId', description: '第三方OpenID', type: 'string')]
    private string $openId;

    #[OA\Property(property: 'unionId', description: '第三方UnionID,如微信多应用互通', type: 'string')]
    private string $unionId;

    #[OA\Property(property: 'nickname', description: '第三方昵称快照', type: 'string')]
    private string $nickname;

    #[OA\Property(property: 'avatar', description: '第三方头像快照', type: 'string')]
    private string $avatar;

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
     * 获取平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * 设置平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub
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
