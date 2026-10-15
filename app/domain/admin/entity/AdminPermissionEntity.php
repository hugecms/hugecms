<?php

declare(strict_types=1);

namespace app\domain\admin\entity;

use Juling\Foundation\Support\ArrayHelper;

class AdminPermissionEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getParentId = 'parent_id'; // 父级ID,0=顶级

    public const string getName = 'name'; // 权限名称

    public const string getCode = 'code'; // 权限标识,如 user:list

    public const string getType = 'type'; // 类型:1=菜单,2=按钮

    public const string getPath = 'path'; // 路由地址

    public const string getIcon = 'icon'; // 菜单图标

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreateTime = 'create_time'; //

    public const string getUpdateTime = 'update_time'; //

    private int $id;

    private int $parentId;

    private string $name;

    private string $code;

    private int $type;

    private string $path;

    private string $icon;

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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
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
