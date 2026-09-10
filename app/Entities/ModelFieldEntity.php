<?php

declare(strict_types=1);

namespace App\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'ModelFieldEntity')]
class ModelFieldEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getModelId = 'model_id'; // 所属模型ID

    public const string getFieldName = 'field_name'; // 字段业务英文名（如：salary）

    public const string getColumnName = 'column_name'; // 物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）

    public const string getFieldLabel = 'field_label'; // 字段显示标签（如：薪资范围）

    public const string getFieldType = 'field_type'; // 字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json

    public const string getColumnType = 'column_type'; // 数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json

    public const string getDefaultValue = 'default_value'; // 默认值

    public const string getIsRequired = 'is_required'; // 是否必填：0否，1是

    public const string getIsUnique = 'is_unique'; // 值是否唯一：0否，1是

    public const string getValidationRules = 'validation_rules'; // 校验规则（JSON），如：{&quot;max&quot;:100,&quot;regex&quot;:&quot;^[A-Z]&quot;}

    public const string getExtraConfig = 'extra_config'; // 额外配置（如select选项：{&quot;options&quot;:[&quot;男&quot;,&quot;女&quot;]}）

    public const string getSortOrder = 'sort_order'; // 表单显示排序

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'modelId', description: '所属模型ID', type: 'integer')]
    private int $modelId;

    #[OA\Property(property: 'fieldName', description: '字段业务英文名（如：salary）', type: 'string')]
    private string $fieldName;

    #[OA\Property(property: 'columnName', description: '物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）', type: 'string')]
    private string $columnName;

    #[OA\Property(property: 'fieldLabel', description: '字段显示标签（如：薪资范围）', type: 'string')]
    private string $fieldLabel;

    #[OA\Property(property: 'fieldType', description: '字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json', type: 'string')]
    private string $fieldType;

    #[OA\Property(property: 'columnType', description: '数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json', type: 'string')]
    private string $columnType;

    #[OA\Property(property: 'defaultValue', description: '默认值', type: 'string')]
    private string $defaultValue;

    #[OA\Property(property: 'isRequired', description: '是否必填：0否，1是', type: 'integer')]
    private int $isRequired;

    #[OA\Property(property: 'isUnique', description: '值是否唯一：0否，1是', type: 'integer')]
    private int $isUnique;

    #[OA\Property(property: 'validationRules', description: '校验规则（JSON），如：{&quot;max&quot;:100,&quot;regex&quot;:&quot;^[A-Z]&quot;}', type: 'string')]
    private string $validationRules;

    #[OA\Property(property: 'extraConfig', description: '额外配置（如select选项：{&quot;options&quot;:[&quot;男&quot;,&quot;女&quot;]}）', type: 'string')]
    private string $extraConfig;

    #[OA\Property(property: 'sortOrder', description: '表单显示排序', type: 'integer')]
    private int $sortOrder;

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
     * 获取所属模型ID
     */
    public function getModelId(): int
    {
        return $this->modelId;
    }

    /**
     * 设置所属模型ID
     */
    public function setModelId(int $modelId): void
    {
        $this->modelId = $modelId;
    }

    /**
     * 获取字段业务英文名（如：salary）
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * 设置字段业务英文名（如：salary）
     */
    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    /**
     * 获取物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * 设置物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）
     */
    public function setColumnName(string $columnName): void
    {
        $this->columnName = $columnName;
    }

    /**
     * 获取字段显示标签（如：薪资范围）
     */
    public function getFieldLabel(): string
    {
        return $this->fieldLabel;
    }

    /**
     * 设置字段显示标签（如：薪资范围）
     */
    public function setFieldLabel(string $fieldLabel): void
    {
        $this->fieldLabel = $fieldLabel;
    }

    /**
     * 获取字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json
     */
    public function getFieldType(): string
    {
        return $this->fieldType;
    }

    /**
     * 设置字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json
     */
    public function setFieldType(string $fieldType): void
    {
        $this->fieldType = $fieldType;
    }

    /**
     * 获取数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json
     */
    public function getColumnType(): string
    {
        return $this->columnType;
    }

    /**
     * 设置数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json
     */
    public function setColumnType(string $columnType): void
    {
        $this->columnType = $columnType;
    }

    /**
     * 获取默认值
     */
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * 设置默认值
     */
    public function setDefaultValue(string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * 获取是否必填：0否，1是
     */
    public function getIsRequired(): int
    {
        return $this->isRequired;
    }

    /**
     * 设置是否必填：0否，1是
     */
    public function setIsRequired(int $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    /**
     * 获取值是否唯一：0否，1是
     */
    public function getIsUnique(): int
    {
        return $this->isUnique;
    }

    /**
     * 设置值是否唯一：0否，1是
     */
    public function setIsUnique(int $isUnique): void
    {
        $this->isUnique = $isUnique;
    }

    /**
     * 获取校验规则（JSON），如：{&quot;max&quot;:100,&quot;regex&quot;:&quot;^[A-Z]&quot;}
     */
    public function getValidationRules(): string
    {
        return $this->validationRules;
    }

    /**
     * 设置校验规则（JSON），如：{&quot;max&quot;:100,&quot;regex&quot;:&quot;^[A-Z]&quot;}
     */
    public function setValidationRules(string $validationRules): void
    {
        $this->validationRules = $validationRules;
    }

    /**
     * 获取额外配置（如select选项：{&quot;options&quot;:[&quot;男&quot;,&quot;女&quot;]}）
     */
    public function getExtraConfig(): string
    {
        return $this->extraConfig;
    }

    /**
     * 设置额外配置（如select选项：{&quot;options&quot;:[&quot;男&quot;,&quot;女&quot;]}）
     */
    public function setExtraConfig(string $extraConfig): void
    {
        $this->extraConfig = $extraConfig;
    }

    /**
     * 获取表单显示排序
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    /**
     * 设置表单显示排序
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
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
