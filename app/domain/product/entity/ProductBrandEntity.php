<?php

declare(strict_types=1);

namespace app\domain\product\entity;

use Juling\Foundation\Support\ArrayHelper;

class ProductBrandEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 品牌名称

    public const string getLogo = 'logo'; // 品牌LOGO

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreateTime = 'create_time'; //

    public const string getUpdateTime = 'update_time'; //

    private int $id;

    private string $name;

    private string $logo;

    private int $sort;

    private int $status;

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

    public function getLogo(): string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
