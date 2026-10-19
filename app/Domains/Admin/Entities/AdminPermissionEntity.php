<?php

declare(strict_types=1);

namespace App\Domains\Admin\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminPermissionEntity')]
class AdminPermissionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getParentId = 'parent_id'; // 父级ID,0=顶级

    public const string getName = 'name'; // 权限名称

    public const string getCode = 'code'; // 权限标识,如 user:list

    public const string getType = 'type'; // 类型:1=菜单,2=按钮

    public const string getPath = 'path'; // 路由地址

    public const string getIcon = 'icon'; // 菜单图标

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreateTime = 'create_time';

    public const string getUpdateTime = 'update_time';

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级ID,0=顶级', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '权限名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'code', description: '权限标识,如 user:list', type: 'string')]
    private string $code;

    #[OA\Property(property: 'type', description: '类型:1=菜单,2=按钮', type: 'integer')]
    private int $type;

    #[OA\Property(property: 'path', description: '路由地址', type: 'string')]
    private string $path;

    #[OA\Property(property: 'icon', description: '菜单图标', type: 'string')]
    private string $icon;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer')]
    private int $status;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
    private string $updateTime;

    /**
     * 获取ID
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * 设置ID
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * 获取父级ID,0=顶级
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID,0=顶级
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取权限名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置权限名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取权限标识,如 user:list
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 设置权限标识,如 user:list
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * 获取类型:1=菜单,2=按钮
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * 设置类型:1=菜单,2=按钮
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取路由地址
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * 设置路由地址
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * 获取菜单图标
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 设置菜单图标
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取状态:0=禁用,1=启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=禁用,1=启用
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * 获取
     */
    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    /**
     * 设置
     */
    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * 获取
     */
    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    /**
     * 设置
     */
    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
