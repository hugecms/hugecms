<?php

declare(strict_types=1);

namespace app\domain\user\request\userSmsCode;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserSmsCodeCreateRequest',
    required: [
        'id',
        'phone',
        'code',
        'scene',
        'expire_at',
        'used_at',
        'ip',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'phone', description: '手机号', type: 'string'),
        new OA\Property(property: 'code', description: '验证码', type: 'string'),
        new OA\Property(property: 'scene', description: '场景:register,login,reset_password', type: 'string'),
        new OA\Property(property: 'expire_at', description: '过期时间', type: 'string'),
        new OA\Property(property: 'used_at', description: '使用时间', type: 'string'),
        new OA\Property(property: 'ip', description: '发送IP,用于防刷', type: 'string'),
    ]
)]
class UserSmsCodeCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'phone' => 'require',
        'code' => 'require',
        'scene' => 'require',
        'expire_at' => 'require',
        'used_at' => 'require',
        'ip' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'phone.require' => '请设置手机号',
        'code.require' => '请设置验证码',
        'scene.require' => '请设置场景:register,login,reset_password',
        'expire_at.require' => '请设置过期时间',
        'used_at.require' => '请设置使用时间',
        'ip.require' => '请设置发送IP,用于防刷',
    ];
}
