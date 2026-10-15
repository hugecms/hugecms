<?php

declare(strict_types=1);

namespace app\domain\user\entity;

use Juling\Foundation\Support\ArrayHelper;

class UserProfileEntity
{
    use ArrayHelper;

    public const string getUserId = 'user_id'; // 用户ID

    public const string getNickname = 'nickname'; // 昵称

    public const string getAvatar = 'avatar'; // 头像

    public const string getGender = 'gender'; // 性别:0=未知,1=男,2=女

    public const string getBirthday = 'birthday'; // 生日

    public const string getBio = 'bio'; // 个人简介

    public const string getLocation = 'location'; // 所在地

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $userId;

    private string $nickname;

    private string $avatar;

    private int $gender;

    private string $birthday;

    private string $bio;

    private string $location;

    private string $createTime;

    private string $updateTime;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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

    public function getGender(): int
    {
        return $this->gender;
    }

    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    public function getBirthday(): string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getBio(): string
    {
        return $this->bio;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
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
