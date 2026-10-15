<?php

declare(strict_types=1);

namespace app\domain\system\request\systemDictItem;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemDictItemUpdateRequest',
    required: [
        'id',
        'dict_id',
        'value',
        'label',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'dict_id', description: '字典类型ID', type: 'integer'),
        new OA\Property(property: 'value', description: '字典值', type: 'string'),
        new OA\Property(property: 'label', description: '字典标签', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class SystemDictItemUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'dict_id' => 'require',
        'value' => 'require',
        'label' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'dict_id.require' => '请设置字典类型ID',
        'value.require' => '请设置字典值',
        'label.require' => '请设置字典标签',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
