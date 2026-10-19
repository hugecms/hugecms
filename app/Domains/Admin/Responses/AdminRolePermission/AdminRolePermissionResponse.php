<?php

declare(strict_types=1);

namespace App\Domains\Admin\Responses\AdminRolePermission;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminRolePermissionResponse')]
class AdminRolePermissionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'roleId', description: '角色ID', type: 'integer')]
    private int $roleId;

    #[OA\Property(property: 'permissionId', description: '权限ID', type: 'integer')]
    private int $permissionId;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    /**
     * 获取角色ID
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * 设置角色ID
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     * 获取权限ID
     */
    public function getPermissionId(): int
    {
        return $this->permissionId;
    }

    /**
     * 设置权限ID
     */
    public function setPermissionId(int $permissionId): void
    {
        $this->permissionId = $permissionId;
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
}
