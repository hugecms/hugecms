<?php

declare(strict_types=1);

namespace app\domain\admin\request\adminUser;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'AdminUserUpdateRequest',
    required: [
        'id',
        'username',
        'email',
        'phone',
        'password',
        'status',
        'last_login_time',
        'last_login_ip',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'username', description: '用户名', type: 'string'),
        new OA\Property(property: 'email', description: '邮箱', type: 'string'),
        new OA\Property(property: 'phone', description: '手机号', type: 'string'),
        new OA\Property(property: 'password', description: '密码哈希', type: 'string'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=正常', type: 'integer'),
        new OA\Property(property: 'last_login_time', description: '最后登录时间', type: 'string'),
        new OA\Property(property: 'last_login_ip', description: '最后登录IP', type: 'string'),
    ]
)]
class AdminUserUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'username' => 'require',
        'email' => 'require',
        'phone' => 'require',
        'password' => 'require',
        'status' => 'require',
        'last_login_time' => 'require',
        'last_login_ip' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'username.require' => '请设置用户名',
        'email.require' => '请设置邮箱',
        'phone.require' => '请设置手机号',
        'password.require' => '请设置密码哈希',
        'status.require' => '请设置状态:0=禁用,1=正常',
        'last_login_time.require' => '请设置最后登录时间',
        'last_login_ip.require' => '请设置最后登录IP',
    ];
}
