<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FriendLinkEntity')]
class FriendLinkEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getCategory = 'category'; // 链接分类（如：合作伙伴、友情链接）

    public const string getSiteName = 'site_name'; // 网站名称

    public const string getSiteUrl = 'site_url'; // 网站URL

    public const string getLogoUrl = 'logo_url'; // 网站Logo URL

    public const string getDescription = 'description'; // 网站描述

    public const string getContactEmail = 'contact_email'; // 联系人邮箱

    public const string getSort = 'sort'; // 排序权重

    public const string getStatus = 'status'; // 状态：0待审核，1已审核，2已拒绝

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'category', description: '链接分类（如：合作伙伴、友情链接）', type: 'string')]
    private string $category;

    #[OA\Property(property: 'siteName', description: '网站名称', type: 'string')]
    private string $siteName;

    #[OA\Property(property: 'siteUrl', description: '网站URL', type: 'string')]
    private string $siteUrl;

    #[OA\Property(property: 'logoUrl', description: '网站Logo URL', type: 'string')]
    private string $logoUrl;

    #[OA\Property(property: 'description', description: '网站描述', type: 'string')]
    private string $description;

    #[OA\Property(property: 'contactEmail', description: '联系人邮箱', type: 'string')]
    private string $contactEmail;

    #[OA\Property(property: 'sort', description: '排序权重', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态：0待审核，1已审核，2已拒绝', type: 'integer')]
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
     * 获取链接分类（如：合作伙伴、友情链接）
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * 设置链接分类（如：合作伙伴、友情链接）
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * 获取网站名称
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * 设置网站名称
     */
    public function setSiteName(string $siteName): void
    {
        $this->siteName = $siteName;
    }

    /**
     * 获取网站URL
     */
    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }

    /**
     * 设置网站URL
     */
    public function setSiteUrl(string $siteUrl): void
    {
        $this->siteUrl = $siteUrl;
    }

    /**
     * 获取网站Logo URL
     */
    public function getLogoUrl(): string
    {
        return $this->logoUrl;
    }

    /**
     * 设置网站Logo URL
     */
    public function setLogoUrl(string $logoUrl): void
    {
        $this->logoUrl = $logoUrl;
    }

    /**
     * 获取网站描述
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * 设置网站描述
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取联系人邮箱
     */
    public function getContactEmail(): string
    {
        return $this->contactEmail;
    }

    /**
     * 设置联系人邮箱
     */
    public function setContactEmail(string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
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
     * 获取状态：0待审核，1已审核，2已拒绝
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0待审核，1已审核，2已拒绝
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
