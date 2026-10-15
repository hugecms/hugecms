<?php

declare(strict_types=1);

namespace app\domain\user\entity;

use Juling\Foundation\Support\ArrayHelper;

class UserFavoriteEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getTargetType = 'target_type'; // 目标类型:article,product

    public const string getTargetId = 'target_id'; // 目标ID

    public const string getCreateTime = 'create_time'; // 

    private int $id;

    private int $userId;

    private string $targetType;

    private int $targetId;

    private string $createTime;

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

    public function getTargetType(): string
    {
        return $this->targetType;
    }

    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    public function getTargetId(): int
    {
        return $this->targetId;
    }

    public function setTargetId(int $targetId): void
    {
        $this->targetId = $targetId;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }
}
