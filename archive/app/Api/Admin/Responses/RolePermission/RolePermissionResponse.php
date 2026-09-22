<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\RolePermission;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RolePermissionResponse')]
class RolePermissionResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'roleId', description: '角色ID', type: 'integer')]
    private int $roleId;

    #[OA\Property(property: 'permissionId', description: '权限ID', type: 'integer')]
    private int $permissionId;

    #[OA\Property(property: 'isDenied', description: '0=允许，1=拒绝（拒绝优先，覆盖性授权）', type: 'integer')]
    private int $isDenied;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

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
     * 获取0=允许，1=拒绝（拒绝优先，覆盖性授权）
     */
    public function getIsDenied(): int
    {
        return $this->isDenied;
    }

    /**
     * 设置0=允许，1=拒绝（拒绝优先，覆盖性授权）
     */
    public function setIsDenied(int $isDenied): void
    {
        $this->isDenied = $isDenied;
    }

    /**
     * 获取创建时间
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * 设置创建时间
     */
    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
