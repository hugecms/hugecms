<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\NavMenu;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NavMenuResponse')]
class NavMenuResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '菜单名称（如：主导航）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'alias', description: '菜单标识（如：main_nav）', type: 'string')]
    private string $alias;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
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
     * 获取菜单名称（如：主导航）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置菜单名称（如：主导航）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取菜单标识（如：main_nav）
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * 设置菜单标识（如：main_nav）
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * 获取描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置描述
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
