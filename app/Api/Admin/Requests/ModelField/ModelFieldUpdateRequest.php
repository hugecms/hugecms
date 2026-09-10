<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\ModelField;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ModelFieldUpdateRequest',
    required: [
        self::getId,
        self::getModelId,
        self::getFieldName,
        self::getColumnName,
        self::getFieldLabel,
        self::getFieldType,
        self::getColumnType,
        self::getDefaultValue,
        self::getIsRequired,
        self::getIsUnique,
        self::getValidationRules,
        self::getExtraConfig,
        self::getSortOrder,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getModelId, description: '所属模型ID', type: 'integer'),
        new OA\Property(property: self::getFieldName, description: '字段业务英文名（如：salary）', type: 'string'),
        new OA\Property(property: self::getColumnName, description: '物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）', type: 'string'),
        new OA\Property(property: self::getFieldLabel, description: '字段显示标签（如：薪资范围）', type: 'string'),
        new OA\Property(property: self::getFieldType, description: '字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json', type: 'string'),
        new OA\Property(property: self::getColumnType, description: '数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json', type: 'string'),
        new OA\Property(property: self::getDefaultValue, description: '默认值', type: 'string'),
        new OA\Property(property: self::getIsRequired, description: '是否必填：0否，1是', type: 'integer'),
        new OA\Property(property: self::getIsUnique, description: '值是否唯一：0否，1是', type: 'integer'),
        new OA\Property(property: self::getValidationRules, description: '校验规则（JSON），如：{&quot;max&quot;:100,&quot;regex&quot;:&quot;^[A-Z]&quot;}', type: 'string'),
        new OA\Property(property: self::getExtraConfig, description: '额外配置（如select选项：{&quot;options&quot;:[&quot;男&quot;,&quot;女&quot;]}）', type: 'string'),
        new OA\Property(property: self::getSortOrder, description: '表单显示排序', type: 'integer'),
    ]
)]
class ModelFieldUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getModelId = 'modelId';

    public const string getFieldName = 'fieldName';

    public const string getColumnName = 'columnName';

    public const string getFieldLabel = 'fieldLabel';

    public const string getFieldType = 'fieldType';

    public const string getColumnType = 'columnType';

    public const string getDefaultValue = 'defaultValue';

    public const string getIsRequired = 'isRequired';

    public const string getIsUnique = 'isUnique';

    public const string getValidationRules = 'validationRules';

    public const string getExtraConfig = 'extraConfig';

    public const string getSortOrder = 'sortOrder';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getModelId => 'required',
            self::getFieldName => 'required',
            self::getColumnName => 'required',
            self::getFieldLabel => 'required',
            self::getFieldType => 'required',
            self::getColumnType => 'required',
            self::getDefaultValue => 'required',
            self::getIsRequired => 'required',
            self::getIsUnique => 'required',
            self::getValidationRules => 'required',
            self::getExtraConfig => 'required',
            self::getSortOrder => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getModelId.'.required' => '请设置所属模型ID',
            self::getFieldName.'.required' => '请设置字段业务英文名（如：salary）',
            self::getColumnName.'.required' => '请设置物理表列名（系统生成，规范 field_{id}，与模型数据表 data_{alias} 的列一一对应）',
            self::getFieldLabel.'.required' => '请设置字段显示标签（如：薪资范围）',
            self::getFieldType.'.required' => '请设置字段类型：text/rich_text/number/integer/date/image/file/select/checkbox/radio/json',
            self::getColumnType.'.required' => '请设置数据库列类型：varchar(255)/text/longtext/int/decimal(10,2)/datetime/tinyint(1)/json',
            self::getDefaultValue.'.required' => '请设置默认值',
            self::getIsRequired.'.required' => '请设置是否必填：0否，1是',
            self::getIsUnique.'.required' => '请设置值是否唯一：0否，1是',
            self::getValidationRules.'.required' => '请设置校验规则（JSON），如：{&quot;max&quot;:100,&quot;regex&quot;:&quot;^[A-Z]&quot;}',
            self::getExtraConfig.'.required' => '请设置额外配置（如select选项：{&quot;options&quot;:[&quot;男&quot;,&quot;女&quot;]}）',
            self::getSortOrder.'.required' => '请设置表单显示排序',
        ];
    }
}
