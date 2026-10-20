<?php

declare(strict_types=1);

namespace App\Domains\System\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'SystemSettingEntity')]
class SystemSettingEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getGroup = 'group'; // 配置分组:basic=基础,seo,upload

    public const string getKey = 'key'; // 配置键,如:site_name

    public const string getTitle = 'title'; // 配置名称,用于后台表单展示

    public const string getValue = 'value'; // 配置值

    public const string getType = 'type'; // 值类型:text,number,select,radio

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'group', description: '配置分组:basic=基础,seo,upload', type: 'string')]
    private string $group;

    #[OA\Property(property: 'key', description: '配置键,如:site_name', type: 'string')]
    private string $key;

    #[OA\Property(property: 'title', description: '配置名称,用于后台表单展示', type: 'string')]
    private string $title;

    #[OA\Property(property: 'value', description: '配置值', type: 'string')]
    private string $value;

    #[OA\Property(property: 'type', description: '值类型:text,number,select,radio', type: 'string')]
    private string $type;

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
     * 获取配置分组:basic=基础,seo,upload
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * 设置配置分组:basic=基础,seo,upload
     */
    public function setGroup(string $group): void
    {
        $this->group = $group;
    }

    /**
     * 获取配置键,如:site_name
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * 设置配置键,如:site_name
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * 获取配置名称,用于后台表单展示
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * 设置配置名称,用于后台表单展示
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * 获取配置值
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 设置配置值
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * 获取值类型:text,number,select,radio
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置值类型:text,number,select,radio
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
