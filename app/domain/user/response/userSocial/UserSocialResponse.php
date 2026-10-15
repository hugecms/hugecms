<?php

declare(strict_types=1);

namespace app\domain\user\response\userSocial;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserSocialResponse')]
class UserSocialResponse
{
    use ArrayHelper;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    public function getOpenId(): string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function getUnionId(): string
    {
        return $this->unionId;
    }

    public function setUnionId(string $unionId): void
    {
        $this->unionId = $unionId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
