<?php

declare(strict_types=1);

namespace app\domain\content\entity;

use Juling\Foundation\Support\ArrayHelper;

class ContentCommentEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 评论用户ID

    public const string getTargetType = 'target_type'; // 目标类型:article,product

    public const string getTargetId = 'target_id'; // 目标ID

    public const string getParentId = 'parent_id'; // 父评论ID,0=顶级

    public const string getContent = 'content'; // 评论内容

    public const string getStatus = 'status'; // 状态:0=待审,1=通过,2=拒绝

    public const string getIp = 'ip'; // 评论IP

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $id;

    private int $userId;

    private string $targetType;

    private int $targetId;

    private int $parentId;

    private string $content;

    private int $status;

    private string $ip;

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

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): void
    {
        $this->ip = $ip;
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
