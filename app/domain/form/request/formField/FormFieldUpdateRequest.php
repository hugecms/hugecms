<?php

declare(strict_types=1);

namespace app\domain\form\request\formField;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'FormFieldUpdateRequest',
    required: [
        'id',
        'form_id',
        'name',
        'field_name',
        'type',
        'options',
        'default_value',
        'is_required',
        'pattern',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'form_id', description: '表单ID', type: 'integer'),
        new OA\Property(property: 'name', description: '字段名称,如:联系电话', type: 'string'),
        new OA\Property(property: 'field_name', description: '字段标识,英文,提交数据的键', type: 'string'),
        new OA\Property(property: 'type', description: '类型:text,textarea,select,radio,checkbox,number,date,image,file', type: 'string'),
        new OA\Property(property: 'options', description: '选项(JSON),select/radio/checkbox用', type: 'string'),
        new OA\Property(property: 'default_value', description: '默认值', type: 'string'),
        new OA\Property(property: 'is_required', description: '必填:0=否,1=是', type: 'integer'),
        new OA\Property(property: 'pattern', description: '正则验证规则', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer'),
    ]
)]
class FormFieldUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'form_id' => 'require',
        'name' => 'require',
        'field_name' => 'require',
        'type' => 'require',
        'options' => 'require',
        'default_value' => 'require',
        'is_required' => 'require',
        'pattern' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'form_id.require' => '请设置表单ID',
        'name.require' => '请设置字段名称,如:联系电话',
        'field_name.require' => '请设置字段标识,英文,提交数据的键',
        'type.require' => '请设置类型:text,textarea,select,radio,checkbox,number,date,image,file',
        'options.require' => '请设置选项(JSON),select/radio/checkbox用',
        'default_value.require' => '请设置默认值',
        'is_required.require' => '请设置必填:0=否,1=是',
        'pattern.require' => '请设置正则验证规则',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=隐藏,1=显示',
    ];
}
