<?php

declare(strict_types=1);

namespace App\Domains\Form\Requests\FormField;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormFieldCreateRequest',
    required: [
        self::getFormId,
        self::getName,
        self::getFieldName,
        self::getType,
        self::getOptions,
        self::getDefaultValue,
        self::getIsRequired,
        self::getPattern,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getFormId, description: '表单ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '字段名称,如:联系电话', type: 'string'),
        new OA\Property(property: self::getFieldName, description: '字段标识,英文,提交数据的键', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:text,textarea,select,radio,checkbox,number,date,image,file', type: 'string'),
        new OA\Property(property: self::getOptions, description: '选项(JSON),select/radio/checkbox用', type: 'string'),
        new OA\Property(property: self::getDefaultValue, description: '默认值', type: 'string'),
        new OA\Property(property: self::getIsRequired, description: '必填:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getPattern, description: '正则验证规则', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=隐藏,1=显示', type: 'integer'),
    ]
)]
class FormFieldCreateRequest extends FormRequest
{
    public const string getFormId = 'formId';

    public const string getName = 'name';

    public const string getFieldName = 'fieldName';

    public const string getType = 'type';

    public const string getOptions = 'options';

    public const string getDefaultValue = 'defaultValue';

    public const string getIsRequired = 'isRequired';

    public const string getPattern = 'pattern';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getFormId => 'required',
            self::getName => 'required',
            self::getFieldName => 'required',
            self::getType => 'required',
            self::getOptions => 'required',
            self::getDefaultValue => 'required',
            self::getIsRequired => 'required',
            self::getPattern => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getFormId.'.required' => '请设置表单ID',
            self::getName.'.required' => '请设置字段名称,如:联系电话',
            self::getFieldName.'.required' => '请设置字段标识,英文,提交数据的键',
            self::getType.'.required' => '请设置类型:text,textarea,select,radio,checkbox,number,date,image,file',
            self::getOptions.'.required' => '请设置选项(JSON),select/radio/checkbox用',
            self::getDefaultValue.'.required' => '请设置默认值',
            self::getIsRequired.'.required' => '请设置必填:0=否,1=是',
            self::getPattern.'.required' => '请设置正则验证规则',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=隐藏,1=显示',
        ];
    }
}
