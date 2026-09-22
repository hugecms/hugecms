<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FormTemplateEntity')]
class FormTemplateEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getName = 'name'; // 表单名称（如：在线报名表）

    public const string getAlias = 'alias'; // 表单标识（用于代码调用）

    public const string getFieldsConfig = 'fields_config'; // 字段配置（JSON数组）：字段名、类型、校验规则、选项等

    public const string getSubmitCount = 'submit_count'; // 提交次数统计

    public const string getIsActive = 'is_active'; // 是否启用：1是，0否

    public const string getSuccessMessage = 'success_message'; // 提交成功提示语

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'name', description: '表单名称（如：在线报名表）', type: 'string')]
    private string $name;

    #[OA\Property(property: 'alias', description: '表单标识（用于代码调用）', type: 'string')]
    private string $alias;

    #[OA\Property(property: 'fieldsConfig', description: '字段配置（JSON数组）：字段名、类型、校验规则、选项等', type: 'string')]
    private string $fieldsConfig;

    #[OA\Property(property: 'submitCount', description: '提交次数统计', type: 'integer')]
    private int $submitCount;

    #[OA\Property(property: 'isActive', description: '是否启用：1是，0否', type: 'integer')]
    private int $isActive;

    #[OA\Property(property: 'successMessage', description: '提交成功提示语', type: 'string')]
    private string $successMessage;

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
     * 获取表单名称（如：在线报名表）
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置表单名称（如：在线报名表）
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取表单标识（用于代码调用）
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * 设置表单标识（用于代码调用）
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * 获取字段配置（JSON数组）：字段名、类型、校验规则、选项等
     */
    public function getFieldsConfig(): string
    {
        return $this->fieldsConfig;
    }

    /**
     * 设置字段配置（JSON数组）：字段名、类型、校验规则、选项等
     */
    public function setFieldsConfig(string $fieldsConfig): void
    {
        $this->fieldsConfig = $fieldsConfig;
    }

    /**
     * 获取提交次数统计
     */
    public function getSubmitCount(): int
    {
        return $this->submitCount;
    }

    /**
     * 设置提交次数统计
     */
    public function setSubmitCount(int $submitCount): void
    {
        $this->submitCount = $submitCount;
    }

    /**
     * 获取是否启用：1是，0否
     */
    public function getIsActive(): int
    {
        return $this->isActive;
    }

    /**
     * 设置是否启用：1是，0否
     */
    public function setIsActive(int $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * 获取提交成功提示语
     */
    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    /**
     * 设置提交成功提示语
     */
    public function setSuccessMessage(string $successMessage): void
    {
        $this->successMessage = $successMessage;
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
