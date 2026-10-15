<?php

declare(strict_types=1);

namespace app\domain\form\entity;

use Juling\Foundation\Support\ArrayHelper;

class FormFieldEntity
{
    use ArrayHelper;

    public const string getId = 'id'; // ID

    public const string getFormId = 'form_id'; // 表单ID

    public const string getName = 'name'; // 字段名称,如:联系电话

    public const string getFieldName = 'field_name'; // 字段标识,英文,提交数据的键

    public const string getType = 'type'; // 类型:text,textarea,select,radio,checkbox,number,date,image,file

    public const string getOptions = 'options'; // 选项(JSON),select/radio/checkbox用

    public const string getDefaultValue = 'default_value'; // 默认值

    public const string getIsRequired = 'is_required'; // 必填:0=否,1=是

    public const string getPattern = 'pattern'; // 正则验证规则

    public const string getSort = 'sort'; // 排序

    public const string getStatus = 'status'; // 状态:0=隐藏,1=显示

    public const string getCreateTime = 'create_time'; // 

    public const string getUpdateTime = 'update_time'; // 

    private int $id;

    private int $formId;

    private string $name;

    private string $fieldName;

    private string $type;

    private string $options;

    private string $defaultValue;

    private int $isRequired;

    private string $pattern;

    private int $sort;

    private int $status;

    private string $createTime;

    private string $updateTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFormId(): int
    {
        return $this->formId;
    }

    public function setFormId(int $formId): void
    {
        $this->formId = $formId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getOptions(): string
    {
        return $this->options;
    }

    public function setOptions(string $options): void
    {
        $this->options = $options;
    }

    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    public function getIsRequired(): int
    {
        return $this->isRequired;
    }

    public function setIsRequired(int $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): void
    {
        $this->createTime = $createTime;
    }

    public function getUpdateTime(): string
    {
        return $this->updateTime;
    }

    public function setUpdateTime(string $updateTime): void
    {
        $this->updateTime = $updateTime;
    }
}
