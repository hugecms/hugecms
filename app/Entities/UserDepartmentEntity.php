<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserDepartmentEntity')]
class UserDepartmentEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getUserId = 'user_id'; // 用户ID

    public const string getDeptId = 'dept_id'; // 部门ID

    public const string getIsPrimary = 'is_primary'; // 是否主属部门：1是（数据范围默认值），0否

    public const string getPosition = 'position'; // 岗位：member专员/supervisor主管/manager经理/director总监

    public const string getEntryDate = 'entry_date'; // 入职日期

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'userId', description: '用户ID', type: 'integer')]
    private int $userId;

    #[OA\Property(property: 'deptId', description: '部门ID', type: 'integer')]
    private int $deptId;

    #[OA\Property(property: 'isPrimary', description: '是否主属部门：1是（数据范围默认值），0否', type: 'integer')]
    private int $isPrimary;

    #[OA\Property(property: 'position', description: '岗位：member专员/supervisor主管/manager经理/director总监', type: 'string')]
    private string $position;

    #[OA\Property(property: 'entryDate', description: '入职日期', type: 'string')]
    private string $entryDate;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

    #[OA\Property(property: 'updatedAt', description: '更新时间', type: 'string')]
    private string $updatedAt;

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
     * 获取部门ID
     */
    public function getDeptId(): int
    {
        return $this->deptId;
    }

    /**
     * 设置部门ID
     */
    public function setDeptId(int $deptId): void
    {
        $this->deptId = $deptId;
    }

    /**
     * 获取是否主属部门：1是（数据范围默认值），0否
     */
    public function getIsPrimary(): int
    {
        return $this->isPrimary;
    }

    /**
     * 设置是否主属部门：1是（数据范围默认值），0否
     */
    public function setIsPrimary(int $isPrimary): void
    {
        $this->isPrimary = $isPrimary;
    }

    /**
     * 获取岗位：member专员/supervisor主管/manager经理/director总监
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * 设置岗位：member专员/supervisor主管/manager经理/director总监
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    /**
     * 获取入职日期
     */
    public function getEntryDate(): string
    {
        return $this->entryDate;
    }

    /**
     * 设置入职日期
     */
    public function setEntryDate(string $entryDate): void
    {
        $this->entryDate = $entryDate;
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

    /**
     * 获取更新时间
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * 设置更新时间
     */
    public function setUpdatedAt(string $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
