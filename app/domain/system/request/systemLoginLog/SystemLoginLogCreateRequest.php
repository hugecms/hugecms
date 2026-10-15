<?php

declare(strict_types=1);

namespace app\domain\system\request\systemLoginLog;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemLoginLogCreateRequest',
    required: [
        'id',
        'user_type',
        'user_id',
        'username',
        'status',
        'ip',
        'user_agent',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_type', description: '账户类型:admin=后台,user=前台', type: 'string'),
        new OA\Property(property: 'user_id', description: '用户ID,账号不存在时为空', type: 'integer'),
        new OA\Property(property: 'username', description: '登录账号(尝试值快照)', type: 'string'),
        new OA\Property(property: 'status', description: '结果:0=失败,1=成功', type: 'integer'),
        new OA\Property(property: 'ip', description: '登录IP', type: 'string'),
        new OA\Property(property: 'user_agent', description: 'User-Agent', type: 'string'),
    ]
)]
class SystemLoginLogCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_type' => 'require',
        'user_id' => 'require',
        'username' => 'require',
        'status' => 'require',
        'ip' => 'require',
        'user_agent' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_type.require' => '请设置账户类型:admin=后台,user=前台',
        'user_id.require' => '请设置用户ID,账号不存在时为空',
        'username.require' => '请设置登录账号(尝试值快照)',
        'status.require' => '请设置结果:0=失败,1=成功',
        'ip.require' => '请设置登录IP',
        'user_agent.require' => '请设置User-Agent',
    ];
}
