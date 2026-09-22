<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ShortLinkClickEntity')]
class ShortLinkClickEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getShortLinkId = 'short_link_id'; // 短链接ID

    public const string getClickIp = 'click_ip'; // 点击者IP

    public const string getUserAgent = 'user_agent'; // 浏览器UA

    public const string getReferer = 'referer'; // 来源页

    public const string getCreatedAt = 'created_at'; // 创建时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'shortLinkId', description: '短链接ID', type: 'integer')]
    private int $shortLinkId;

    #[OA\Property(property: 'clickIp', description: '点击者IP', type: 'string')]
    private string $clickIp;

    #[OA\Property(property: 'userAgent', description: '浏览器UA', type: 'string')]
    private string $userAgent;

    #[OA\Property(property: 'referer', description: '来源页', type: 'string')]
    private string $referer;

    #[OA\Property(property: 'createdAt', description: '创建时间', type: 'string')]
    private string $createdAt;

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
     * 获取短链接ID
     */
    public function getShortLinkId(): int
    {
        return $this->shortLinkId;
    }

    /**
     * 设置短链接ID
     */
    public function setShortLinkId(int $shortLinkId): void
    {
        $this->shortLinkId = $shortLinkId;
    }

    /**
     * 获取点击者IP
     */
    public function getClickIp(): string
    {
        return $this->clickIp;
    }

    /**
     * 设置点击者IP
     */
    public function setClickIp(string $clickIp): void
    {
        $this->clickIp = $clickIp;
    }

    /**
     * 获取浏览器UA
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * 设置浏览器UA
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * 获取来源页
     */
    public function getReferer(): string
    {
        return $this->referer;
    }

    /**
     * 设置来源页
     */
    public function setReferer(string $referer): void
    {
        $this->referer = $referer;
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
}
