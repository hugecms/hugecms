<?php

declare(strict_types=1);

namespace app\domain\admin\request\adminPermission;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'AdminPermissionUpdateRequest',
    required: [
        'id',
        'parent_id',
        'name',
        'code',
        'type',
        'path',
        'icon',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'parent_id', description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: 'name', description: '权限名称', type: 'string'),
        new OA\Property(property: 'code', description: '权限标识,如 user:list', type: 'string'),
        new OA\Property(property: 'type', description: '类型:1=菜单,2=按钮', type: 'integer'),
        new OA\Property(property: 'path', description: '路由地址', type: 'string'),
        new OA\Property(property: 'icon', description: '菜单图标', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class AdminPermissionUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'parent_id' => 'require',
        'name' => 'require',
        'code' => 'require',
        'type' => 'require',
        'path' => 'require',
        'icon' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'parent_id.require' => '请设置父级ID,0=顶级',
        'name.require' => '请设置权限名称',
        'code.require' => '请设置权限标识,如 user:list',
        'type.require' => '请设置类型:1=菜单,2=按钮',
        'path.require' => '请设置路由地址',
        'icon.require' => '请设置菜单图标',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
