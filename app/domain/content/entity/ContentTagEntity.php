<?php

declare(strict_types=1);

namespace app\domain\content\entity;

use Juling\Foundation\Support\ArrayHelper;

class ContentTagEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 标签名

    public const string getCreateTime = 'create_time'; //

    public const string getUpdateTime = 'update_time'; //

    private int $id;

    private string $name;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
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
