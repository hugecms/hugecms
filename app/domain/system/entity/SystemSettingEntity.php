<?php

declare(strict_types=1);

namespace app\domain\system\entity;

use Juling\Foundation\Support\ArrayHelper;

class SystemSettingEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getGroup = 'group'; // 配置分组:basic=基础,seo,upload

    public const string getKey = 'key'; // 配置键,如:site_name

    public const string getTitle = 'title'; // 配置名称,用于后台表单展示

    public const string getValue = 'value'; // 配置值

    public const string getType = 'type'; // 值类型:text,number,select,radio

    public const string getCreateTime = 'create_time'; //

    public const string getUpdateTime = 'update_time'; //

    private int $id;

    private string $group;

    private string $key;

    private string $title;

    private string $value;

    private string $type;

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

    public function getGroup(): string
    {
        return $this->group;
    }

    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
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
