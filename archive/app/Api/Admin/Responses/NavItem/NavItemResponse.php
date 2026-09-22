<?php

declare(strict_types=1);

namespace App\Api\Admin\Responses\NavItem;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'NavItemResponse')]
class NavItemResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'menuId', description: '所属菜单集', type: 'integer')]
    private int $menuId;

    #[OA\Property(property: 'parentId', description: '父级ID（0代表顶级）', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'title', description: '菜单显示标题', type: 'string')]
    private string $title;

    #[OA\Property(property: 'linkType', description: '链接类型：custom自定义/content内容/term分类', type: 'string')]
    private string $linkType;

    #[OA\Property(property: 'linkValue', description: '链接目标值（自定义URL 或 content_id/term_id）', type: 'string')]
    private string $linkValue;

    #[OA\Property(property: 'openType', description: '打开方式：0本窗口，1新窗口', type: 'integer')]
    private int $openType;

    #[OA\Property(property: 'icon', description: '小图标CSS类', type: 'string')]
    private string $icon;

    #[OA\Property(property: 'isActive', description: '是否启用：1是，0否', type: 'integer')]
    private int $isActive;

    #[OA\Property(property: 'sort', description: '排序权重', type: 'integer')]
    private int $sort;

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
     * 获取所属菜单集
     */
    public function getMenuId(): int
    {
        return $this->menuId;
    }

    /**
     * 设置所属菜单集
     */
    public function setMenuId(int $menuId): void
    {
        $this->menuId = $menuId;
    }

    /**
     * 获取父级ID（0代表顶级）
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * 设置父级ID（0代表顶级）
     */
    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * 获取菜单显示标题
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置菜单显示标题
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取链接类型：custom自定义/content内容/term分类
     */
    public function getLinkType(): string
    {
        return $this->linkType;
    }

    /**
     * 设置链接类型：custom自定义/content内容/term分类
     */
    public function setLinkType(string $linkType): void
    {
        $this->linkType = $linkType;
    }

    /**
     * 获取链接目标值（自定义URL 或 content_id/term_id）
     */
    public function getLinkValue(): string
    {
        return $this->linkValue;
    }

    /**
     * 设置链接目标值（自定义URL 或 content_id/term_id）
     */
    public function setLinkValue(string $linkValue): void
    {
        $this->linkValue = $linkValue;
    }

    /**
     * 获取打开方式：0本窗口，1新窗口
     */
    public function getOpenType(): int
    {
        return $this->openType;
    }

    /**
     * 设置打开方式：0本窗口，1新窗口
     */
    public function setOpenType(int $openType): void
    {
        $this->openType = $openType;
    }

    /**
     * 获取小图标CSS类
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * 设置小图标CSS类
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * 获取是否启用：1是，0否
     */
    public function getIsActive(): int
    {
        return $this->isActive;
    }

    /**
     * 设置是否启用：1是，0否
     */
    public function setIsActive(int $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * 获取排序权重
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序权重
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
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
