<?php

declare(strict_types=1);

namespace app\domain\user\entity;

use Juling\Foundation\Support\ArrayHelper;

class UserSocialEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getPlatform = 'platform'; // 平台:wechat,qq,weibo,github

    public const string getOpenId = 'open_id'; // 第三方OpenID

    public const string getUnionId = 'union_id'; // 第三方UnionID,如微信多应用互通

    public const string getNickname = 'nickname'; // 第三方昵称快照

    public const string getAvatar = 'avatar'; // 第三方头像快照

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $id;

    private int $userId;

    private string $platform;

    private string $openId;

    private string $unionId;

    private string $nickname;

    private string $avatar;

    private string $createTime;

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
