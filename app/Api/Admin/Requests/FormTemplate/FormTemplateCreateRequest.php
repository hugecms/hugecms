<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\FormTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'FormTemplateCreateRequest',
    required: [
        self::getName,
        self::getAlias,
        self::getFieldsConfig,
        self::getSubmitCount,
        self::getIsActive,
        self::getSuccessMessage,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '表单名称（如：在线报名表）', type: 'string'),
        new OA\Property(property: self::getAlias, description: '表单标识（用于代码调用）', type: 'string'),
        new OA\Property(property: self::getFieldsConfig, description: '字段配置（JSON数组）：字段名、类型、校验规则、选项等', type: 'string'),
        new OA\Property(property: self::getSubmitCount, description: '提交次数统计', type: 'integer'),
        new OA\Property(property: self::getIsActive, description: '是否启用：1是，0否', type: 'integer'),
        new OA\Property(property: self::getSuccessMessage, description: '提交成功提示语', type: 'string'),
    ]
)]
class FormTemplateCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getAlias = 'alias';

    public const string getFieldsConfig = 'fieldsConfig';

    public const string getSubmitCount = 'submitCount';

    public const string getIsActive = 'isActive';

    public const string getSuccessMessage = 'successMessage';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getAlias => 'required',
            self::getFieldsConfig => 'required',
            self::getSubmitCount => 'required',
            self::getIsActive => 'required',
            self::getSuccessMessage => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置表单名称（如：在线报名表）',
            self::getAlias.'.required' => '请设置表单标识（用于代码调用）',
            self::getFieldsConfig.'.required' => '请设置字段配置（JSON数组）：字段名、类型、校验规则、选项等',
            self::getSubmitCount.'.required' => '请设置提交次数统计',
            self::getIsActive.'.required' => '请设置是否启用：1是，0否',
            self::getSuccessMessage.'.required' => '请设置提交成功提示语',
        ];
    }
}
