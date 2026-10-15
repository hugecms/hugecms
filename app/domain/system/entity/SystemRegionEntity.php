<?php

declare(strict_types=1);

namespace app\domain\system\entity;

use Juling\Foundation\Support\ArrayHelper;

class SystemRegionEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getParentId = 'parent_id'; // 父级ID,0=省级

    public const string getName = 'name'; // 区域名称

    public const string getLevel = 'level'; // 层级:1=省,2=市,3=区县

    public const string getCode = 'code'; // 行政区划码

    public const string getSort = 'sort'; // 排序

    public const string getCreateTime = 'create_time'; // 

    private int $id;

    private int $parentId;

    private string $name;

    private int $level;

    private string $code;

    private int $sort;

    private string $createTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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
