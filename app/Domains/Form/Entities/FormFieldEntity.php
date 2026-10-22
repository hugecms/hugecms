<?php

declare(strict_types=1);

namespace App\Domains\Form\Entities;

use Juling\Foundation\Support\Traits\HasSerializableAttributes;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FormFieldEntity')]
class FormFieldEntity implements \JsonSerializable
{
    use HasSerializableAttributes;

    public const string getId = 'id'; // ID

    public const string getFormId = 'form_id'; // 表单ID

    public const string getName = 'name'; // 字段名称,如:联系电话

    public const string getFieldName = 'field_name'; // 字段标识,英文,提交数据的键

    public const string getType = 'type'; // 类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件

    public const string getOptions = 'options'; // 选项(JSON),select/radio/checkbox用

    public const string getDefaultValue = 'default_value'; // 默认值

    public const string getIsRequired = 'is_required'; // 必填:0=否,1=是

    public const string getPattern = 'pattern'; // 正则验证规则

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=隐藏,1=显示

    public const string getCreatedAt = 'created_at'; // 创建时间

    public const string getUpdatedAt = 'updated_at'; // 更新时间

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'formId', description: '表单ID', type: 'integer')]
    private int $formId;

    #[OA\Property(property: 'name', description: '字段名称,如:联系电话', type: 'string')]
    private string $name;

    #[OA\Property(property: 'fieldName', description: '字段标识,英文,提交数据的键', type: 'string')]
    private string $fieldName;

    #[OA\Property(property: 'type', description: '类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件', type: 'string')]
    private string $type;

    #[OA\Property(property: 'options', description: '选项(JSON),select/radio/checkbox用', type: 'string')]
    private string $options;

    #[OA\Property(property: 'defaultValue', description: '默认值', type: 'string')]
    private string $defaultValue;

    #[OA\Property(property: 'isRequired', description: '必填:0=否,1=是', type: 'integer')]
    private int $isRequired;

    #[OA\Property(property: 'pattern', description: '正则验证规则', type: 'string')]
    private string $pattern;

    #[OA\Property(property: 'sort', description: '排序', type: 'integer')]
    private int $sort;

    #[OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer')]
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
     * 获取表单ID
     */
    public function getFormId(): int
    {
        return $this->formId;
    }

    /**
     * 设置表单ID
     */
    public function setFormId(int $formId): void
    {
        $this->formId = $formId;
    }

    /**
     * 获取字段名称,如:联系电话
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置字段名称,如:联系电话
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取字段标识,英文,提交数据的键
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * 设置字段标识,英文,提交数据的键
     */
    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    /**
     * 获取类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * 设置类型:text=文本,textarea=多行文本,select=下拉单选,radio=单选,checkbox=多选,number=数字,date=日期,image=图片,file=文件
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * 获取选项(JSON),select/radio/checkbox用
     */
    public function getOptions(): string
    {
        return $this->options;
    }

    /**
     * 设置选项(JSON),select/radio/checkbox用
     */
    public function setOptions(string $options): void
    {
        $this->options = $options;
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
     * 获取必填:0=否,1=是
     */
    public function getIsRequired(): int
    {
        return $this->isRequired;
    }

    /**
     * 设置必填:0=否,1=是
     */
    public function setIsRequired(int $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    /**
     * 获取正则验证规则
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * 设置正则验证规则
     */
    public function setPattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    /**
     * 获取排序
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * 设置排序
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * 获取状态:0=隐藏,1=显示
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * 设置状态:0=隐藏,1=显示
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
