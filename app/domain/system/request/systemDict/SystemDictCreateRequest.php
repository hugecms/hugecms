<?php

declare(strict_types=1);

namespace app\domain\system\request\systemDict;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemDictCreateRequest',
    required: [
        'id',
        'type',
        'name',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'type', description: '类型标识,如:gender', type: 'string'),
        new OA\Property(property: 'name', description: '类型名称', type: 'string'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class SystemDictCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'type' => 'require',
        'name' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'type.require' => '请设置类型标识,如:gender',
        'name.require' => '请设置类型名称',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
