<?php

declare(strict_types=1);

namespace App\Domains\Admin\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AdminUserRoleEntity')]
class AdminUserRoleEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getAdminUserId = 'admin_user_id'; // 管理员ID

    public const string getRoleId = 'role_id'; // 角色ID

    public const string getCreateTime = 'create_time';

    #[OA\Property(property: 'adminUserId', description: '管理员ID', type: 'integer')]
    private int $adminUserId;

    #[OA\Property(property: 'roleId', description: '角色ID', type: 'integer')]
    private int $roleId;

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    /**
     * 获取管理员ID
     */
    public function getAdminUserId(): int
    {
        return $this->adminUserId;
    }

    /**
     * 设置管理员ID
     */
    public function setAdminUserId(int $adminUserId): void
    {
        $this->adminUserId = $adminUserId;
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
