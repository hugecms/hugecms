<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\UserRole;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserRoleResponse')]
class UserRoleResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'roleId', description: '角色ID', type: 'integer')]
    private int $roleId;

    #[OA\Property(property: 'dataScope', description: '数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）', type: 'string')]
    private string $dataScope;

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
     * 获取用户ID
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
     * 获取数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）
     */
    public function getDataScope(): string
    {
        return $this->dataScope;
    }

    /**
     * 设置数据范围：self仅自己/all全部/custom自定义（部门体系已精简，dept系列待插件化恢复）
     */
    public function setDataScope(string $dataScope): void
    {
        $this->dataScope = $dataScope;
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
