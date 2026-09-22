<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShortLinkEntity')]
class ShortLinkEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getShortCode = 'short_code'; // 短链代码（如：abc123）

    public const string getTargetUrl = 'target_url'; // 原始目标URL

    public const string getTitle = 'title'; // 链接标题/备注

    public const string getClickCount = 'click_count'; // 点击次数

    public const string getQrCodePath = 'qr_code_path'; // 二维码图片存储路径

    public const string getExpireAt = 'expire_at'; // 过期时间（NULL永不过期）

    public const string getStatus = 'status'; // 状态：0停用，1启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shortCode', description: '短链代码（如：abc123）', type: 'string')]
    private string $shortCode;

    #[OA\Property(property: 'targetUrl', description: '原始目标URL', type: 'string')]
    private string $targetUrl;

    #[OA\Property(property: 'title', description: '链接标题/备注', type: 'string')]
    private string $title;

    #[OA\Property(property: 'clickCount', description: '点击次数', type: 'integer')]
    private int $clickCount;

    #[OA\Property(property: 'qrCodePath', description: '二维码图片存储路径', type: 'string')]
    private string $qrCodePath;

    #[OA\Property(property: 'expireAt', description: '过期时间（NULL永不过期）', type: 'string')]
    private string $expireAt;

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
     * 获取短链代码（如：abc123）
     */
    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    /**
     * 设置短链代码（如：abc123）
     */
    public function setShortCode(string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    /**
     * 获取原始目标URL
     */
    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    /**
     * 设置原始目标URL
     */
    public function setTargetUrl(string $targetUrl): void
    {
        $this->targetUrl = $targetUrl;
    }

    /**
     * 获取链接标题/备注
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置链接标题/备注
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取点击次数
     */
    public function getClickCount(): int
    {
        return $this->clickCount;
    }

    /**
     * 设置点击次数
     */
    public function setClickCount(int $clickCount): void
    {
        $this->clickCount = $clickCount;
    }

    /**
     * 获取二维码图片存储路径
     */
    public function getQrCodePath(): string
    {
        return $this->qrCodePath;
    }

    /**
     * 设置二维码图片存储路径
     */
    public function setQrCodePath(string $qrCodePath): void
    {
        $this->qrCodePath = $qrCodePath;
    }

    /**
     * 获取过期时间（NULL永不过期）
     */
    public function getExpireAt(): string
    {
        return $this->expireAt;
    }

    /**
     * 设置过期时间（NULL永不过期）
     */
    public function setExpireAt(string $expireAt): void
    {
        $this->expireAt = $expireAt;
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
