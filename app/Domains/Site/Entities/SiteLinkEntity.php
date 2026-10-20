<?php

declare(strict_types=1);

namespace App\Domains\Site\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SiteLinkEntity')]
class SiteLinkEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 站点名称

    public const string getUrl = 'url'; // 链接地址

    public const string getLogo = 'logo'; // 站点LOGO

    public const string getDescription = 'description'; // 描述

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=禁用,1=启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '站点名称', type: 'string')]
    private string $name;

    #[OA\Property(property: 'url', description: '链接地址', type: 'string')]
    private string $url;

    #[OA\Property(property: 'logo', description: '站点LOGO', type: 'string')]
    private string $logo;

    #[OA\Property(property: 'description', description: '描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer')]
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
     * 获取站点名称
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置站点名称
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
     * 获取站点LOGO
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * 设置站点LOGO
     */
    public function setLogo(string $logo): void
    {
        $this->logo = $logo;
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
