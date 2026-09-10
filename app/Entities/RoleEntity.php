<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'RoleEntity')]
class RoleEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 角色名称（如：主编、运营）

    public const string getAlias = 'alias'; // 角色标识（如：chief_editor）

    public const string getIsSystem = 'is_system'; // 是否系统内置（不可删除）：1是，0否

    public const string getDescription = 'description'; // 角色描述

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '角色名称（如：主编、运营）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'alias', description: '角色标识（如：chief_editor）', type: 'string')]
    private string $alias;

    #[OA\Property(property: 'isSystem', description: '是否系统内置（不可删除）：1是，0否', type: 'integer')]
    private int $isSystem;

    #[OA\Property(property: 'description', description: '角色描述', type: 'string')]
    private string $description;

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
     * 获取角色名称（如：主编、运营）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置角色名称（如：主编、运营）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取角色标识（如：chief_editor）
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * 设置角色标识（如：chief_editor）
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * 获取是否系统内置（不可删除）：1是，0否
     */
    public function getIsSystem(): int
    {
        return $this->isSystem;
    }

    /**
     * 设置是否系统内置（不可删除）：1是，0否
     */
    public function setIsSystem(int $isSystem): void
    {
        $this->isSystem = $isSystem;
    }

    /**
     * 获取角色描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置角色描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
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
