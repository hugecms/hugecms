<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'PageTemplateEntity')]
class PageTemplateEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getTemplateName = 'template_name'; // 模板名称

    public const string getTemplateCode = 'template_code'; // 模板代码（唯一标识）

    public const string getCategory = 'category'; // 类别：page页面/post文章/term分类模板

    public const string getPreviewImage = 'preview_image'; // 预览图URL

    public const string getContent = 'content'; // 模板内容（HTML/JSON结构）

    public const string getIsDefault = 'is_default'; // 是否默认模板

    public const string getIsSystem = 'is_system'; // 是否系统内置

    public const string getStatus = 'status'; // 状态：0停用，1启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'templateName', description: '模板名称', type: 'string')]
    private string $templateName;

    #[OA\Property(property: 'templateCode', description: '模板代码（唯一标识）', type: 'string')]
    private string $templateCode;

    #[OA\Property(property: 'category', description: '类别：page页面/post文章/term分类模板', type: 'string')]
    private string $category;

    #[OA\Property(property: 'previewImage', description: '预览图URL', type: 'string')]
    private string $previewImage;

    #[OA\Property(property: 'content', description: '模板内容（HTML/JSON结构）', type: 'string')]
    private string $content;

    #[OA\Property(property: 'isDefault', description: '是否默认模板', type: 'integer')]
    private int $isDefault;

    #[OA\Property(property: 'isSystem', description: '是否系统内置', type: 'integer')]
    private int $isSystem;

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
     * 获取模板名称
     */
    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * 设置模板名称
     */
    public function setTemplateName(string $templateName): void
    {
        $this->templateName = $templateName;
    }

    /**
     * 获取模板代码（唯一标识）
     */
    public function getTemplateCode(): string
    {
        return $this->templateCode;
    }

    /**
     * 设置模板代码（唯一标识）
     */
    public function setTemplateCode(string $templateCode): void
    {
        $this->templateCode = $templateCode;
    }

    /**
     * 获取类别：page页面/post文章/term分类模板
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * 设置类别：page页面/post文章/term分类模板
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * 获取预览图URL
     */
    public function getPreviewImage(): string
    {
        return $this->previewImage;
    }

    /**
     * 设置预览图URL
     */
    public function setPreviewImage(string $previewImage): void
    {
        $this->previewImage = $previewImage;
    }

    /**
     * 获取模板内容（HTML/JSON结构）
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 设置模板内容（HTML/JSON结构）
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * 获取是否默认模板
     */
    public function getIsDefault(): int
    {
        return $this->isDefault;
    }

    /**
     * 设置是否默认模板
     */
    public function setIsDefault(int $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    /**
     * 获取是否系统内置
     */
    public function getIsSystem(): int
    {
        return $this->isSystem;
    }

    /**
     * 设置是否系统内置
     */
    public function setIsSystem(int $isSystem): void
    {
        $this->isSystem = $isSystem;
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
