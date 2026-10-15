<?php

declare(strict_types=1);

namespace app\domain\admin\request\adminRole;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'AdminRoleCreateRequest',
    required: [
        'id',
        'name',
        'code',
        'description',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '角色名称', type: 'string'),
        new OA\Property(property: 'code', description: '角色标识', type: 'string'),
        new OA\Property(property: 'description', description: '角色描述', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class AdminRoleCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'code' => 'require',
        'description' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'name.require' => '请设置角色名称',
        'code.require' => '请设置角色标识',
        'description.require' => '请设置角色描述',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
