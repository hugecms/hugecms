<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SensitiveWordEntity')]
class SensitiveWordEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getWord = 'word'; // 敏感词

    public const string getCategory = 'category'; // 分类：politics/porn/violence/spam/fraud/custom

    public const string getSeverity = 'severity'; // 严重程度：1警告，2拦截，3封禁

    public const string getStatus = 'status'; // 状态：0停用，1启用

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'word', description: '敏感词', type: 'string')]
    private string $word;

    #[OA\Property(property: 'category', description: '分类：politics/porn/violence/spam/fraud/custom', type: 'string')]
    private string $category;

    #[OA\Property(property: 'severity', description: '严重程度：1警告，2拦截，3封禁', type: 'integer')]
    private int $severity;

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
     * 获取敏感词
     */
    public function getWord(): string
    {
        return $this->word;
    }

    /**
     * 设置敏感词
     */
    public function setWord(string $word): void
    {
        $this->word = $word;
    }

    /**
     * 获取分类：politics/porn/violence/spam/fraud/custom
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * 设置分类：politics/porn/violence/spam/fraud/custom
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * 获取严重程度：1警告，2拦截，3封禁
     */
    public function getSeverity(): int
    {
        return $this->severity;
    }

    /**
     * 设置严重程度：1警告，2拦截，3封禁
     */
    public function setSeverity(int $severity): void
    {
        $this->severity = $severity;
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
