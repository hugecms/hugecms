<?php

declare(strict_types=1);

namespace App\Domains\Site\Responses\SiteNav;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SiteNavResponse')]
class SiteNavResponse implements \JsonSerializable
{
    use HasSerializableAttributes;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'parentId', description: '父级ID,0=顶级', type: 'integer')]
    private int $parentId;

    #[OA\Property(property: 'name', description: '导航名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'url', description: '链接地址', type: 'string')]
    private string $url;

    #[OA\Property(property: 'position', description: '展示位置:header=头部,footer=底部', type: 'string')]
    private string $position;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer')]
    private int $status;

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
     * 获取导航名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置导航名称
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取链接地址
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * 设置链接地址
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * 获取展示位置:header=头部,footer=底部
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * 设置展示位置:header=头部,footer=底部
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
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
     * 获取状态:0=隐藏,1=显示
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=隐藏,1=显示
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
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
