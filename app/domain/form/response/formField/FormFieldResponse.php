<?php

declare(strict_types=1);

namespace app\domain\form\response\formField;

use Juling\Foundation\Support\ArrayHelper;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'FormFieldResponse')]
class FormFieldResponse
{
    use ArrayHelper;

    #[OA\Property(property: 'id', description: 'ID', type: 'integer')]
    private int $id;

    #[OA\Property(property: 'formId', description: '表单ID', type: 'integer')]
    private int $formId;

    #[OA\Property(property: 'name', description: '字段名称,如:联系电话', type: 'string')]
    private string $name;

    #[OA\Property(property: 'fieldName', description: '字段标识,英文,提交数据的键', type: 'string')]
    private string $fieldName;

    #[OA\Property(property: 'type', description: '类型:text,textarea,select,radio,checkbox,number,date,image,file', type: 'string')]
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

    #[OA\Property(property: 'createTime', description: '', type: 'string')]
    private string $createTime;

    #[OA\Property(property: 'updateTime', description: '', type: 'string')]
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
