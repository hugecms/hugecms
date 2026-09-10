<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SiteEntity')]
class SiteEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getSiteName = 'site_name'; // 站点名称

    public const string getSiteCode = 'site_code'; // 站点代码（子域名或标识）

    public const string getDomain = 'domain'; // 主域名（如：www.example.com）

    public const string getDomains = 'domains'; // 附加域名列表（JSON数组）

    public const string getSiteLogo = 'site_logo'; // 站点Logo

    public const string getFavicon = 'favicon'; // 站点图标

    public const string getTimezone = 'timezone'; // 时区

    public const string getLanguage = 'language'; // 默认语言

    public const string getTemplateId = 'template_id'; // 当前使用的模板ID（关联 page_templates，逻辑关联）

    public const string getConfig = 'config'; // 站点配置（SEO默认值、社交分享等）

    public const string getStatus = 'status'; // 状态：0停用，1启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'siteName', description: '站点名称', type: 'string')]
    private string $siteName;

    #[OA\Property(property: 'siteCode', description: '站点代码（子域名或标识）', type: 'string')]
    private string $siteCode;

    #[OA\Property(property: 'domain', description: '主域名（如：www.example.com）', type: 'string')]
    private string $domain;

    #[OA\Property(property: 'domains', description: '附加域名列表（JSON数组）', type: 'string')]
    private string $domains;

    #[OA\Property(property: 'siteLogo', description: '站点Logo', type: 'string')]
    private string $siteLogo;

    #[OA\Property(property: 'favicon', description: '站点图标', type: 'string')]
    private string $favicon;

    #[OA\Property(property: 'timezone', description: '时区', type: 'string')]
    private string $timezone;

    #[OA\Property(property: 'language', description: '默认语言', type: 'string')]
    private string $language;

    #[OA\Property(property: 'templateId', description: '当前使用的模板ID（关联 page_templates，逻辑关联）', type: 'integer')]
    private int $templateId;

    #[OA\Property(property: 'config', description: '站点配置（SEO默认值、社交分享等）', type: 'string')]
    private string $config;

    #[OA\Property(property: 'status', description: '状态：0停用，1启用', type: 'integer')]
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
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * 设置站点名称
     */
    public function setSiteName(string $siteName): void
    {
        $this->siteName = $siteName;
    }

    /**
     * 获取站点代码（子域名或标识）
     */
    public function getSiteCode(): string
    {
        return $this->siteCode;
    }

    /**
     * 设置站点代码（子域名或标识）
     */
    public function setSiteCode(string $siteCode): void
    {
        $this->siteCode = $siteCode;
    }

    /**
     * 获取主域名（如：www.example.com）
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * 设置主域名（如：www.example.com）
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * 获取附加域名列表（JSON数组）
     */
    public function getDomains(): string
    {
        return $this->domains;
    }

    /**
     * 设置附加域名列表（JSON数组）
     */
    public function setDomains(string $domains): void
    {
        $this->domains = $domains;
    }

    /**
     * 获取站点Logo
     */
    public function getSiteLogo(): string
    {
        return $this->siteLogo;
    }

    /**
     * 设置站点Logo
     */
    public function setSiteLogo(string $siteLogo): void
    {
        $this->siteLogo = $siteLogo;
    }

    /**
     * 获取站点图标
     */
    public function getFavicon(): string
    {
        return $this->favicon;
    }

    /**
     * 设置站点图标
     */
    public function setFavicon(string $favicon): void
    {
        $this->favicon = $favicon;
    }

    /**
     * 获取时区
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * 设置时区
     */
    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    /**
     * 获取默认语言
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * 设置默认语言
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * 获取当前使用的模板ID（关联 page_templates，逻辑关联）
     */
    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    /**
     * 设置当前使用的模板ID（关联 page_templates，逻辑关联）
     */
    public function setTemplateId(int $templateId): void
    {
        $this->templateId = $templateId;
    }

    /**
     * 获取站点配置（SEO默认值、社交分享等）
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * 设置站点配置（SEO默认值、社交分享等）
     */
    public function setConfig(string $config): void
    {
        $this->config = $config;
    }

    /**
     * 获取状态：0停用，1启用
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态：0停用，1启用
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
