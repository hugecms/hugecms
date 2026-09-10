<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'OptionEntity')]
class OptionEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getOptionKey = 'option_key'; // 配置键名（storage_config/smtp_config/comment_config等）

    public const string getOptionValue = 'option_value'; // 配置值（支持JSON复杂结构）

    public const string getAutoload = 'autoload'; // 启动时自动加载：0否，1是（配合 Laravel Cache 预热）

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'optionKey', description: '配置键名（storage_config/smtp_config/comment_config等）', type: 'string')]
    private string $optionKey;

    #[OA\Property(property: 'optionValue', description: '配置值（支持JSON复杂结构）', type: 'string')]
    private string $optionValue;

    #[OA\Property(property: 'autoload', description: '启动时自动加载：0否，1是（配合 Laravel Cache 预热）', type: 'integer')]
    private int $autoload;

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
     * 获取配置键名（storage_config/smtp_config/comment_config等）
     */
    public function getOptionKey(): string
    {
        return $this->optionKey;
    }

    /**
     * 设置配置键名（storage_config/smtp_config/comment_config等）
     */
    public function setOptionKey(string $optionKey): void
    {
        $this->optionKey = $optionKey;
    }

    /**
     * 获取配置值（支持JSON复杂结构）
     */
    public function getOptionValue(): string
    {
        return $this->optionValue;
    }

    /**
     * 设置配置值（支持JSON复杂结构）
     */
    public function setOptionValue(string $optionValue): void
    {
        $this->optionValue = $optionValue;
    }

    /**
     * 获取启动时自动加载：0否，1是（配合 Laravel Cache 预热）
     */
    public function getAutoload(): int
    {
        return $this->autoload;
    }

    /**
     * 设置启动时自动加载：0否，1是（配合 Laravel Cache 预热）
     */
    public function setAutoload(int $autoload): void
    {
        $this->autoload = $autoload;
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
