<?php

declare(strict_types=1);

namespace app\domain\system\request\systemOperationLog;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'SystemOperationLogUpdateRequest',
    required: [
        'id',
        'user_type',
        'user_id',
        'username',
        'title',
        'controller',
        'action',
        'method',
        'url',
        'params',
        'status',
        'cost_time',
        'ip',
        'user_agent',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_type', description: '账户类型:admin=后台,user=前台', type: 'string'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'username', description: '操作人账号快照', type: 'string'),
        new OA\Property(property: 'title', description: '操作名称,如:删除用户', type: 'string'),
        new OA\Property(property: 'controller', description: '控制器', type: 'string'),
        new OA\Property(property: 'action', description: '方法', type: 'string'),
        new OA\Property(property: 'method', description: '请求方式', type: 'string'),
        new OA\Property(property: 'url', description: '请求地址', type: 'string'),
        new OA\Property(property: 'params', description: '请求参数(JSON)', type: 'string'),
        new OA\Property(property: 'status', description: '结果:0=失败,1=成功', type: 'integer'),
        new OA\Property(property: 'cost_time', description: '耗时(毫秒)', type: 'integer'),
        new OA\Property(property: 'ip', description: '操作IP', type: 'string'),
        new OA\Property(property: 'user_agent', description: 'User-Agent', type: 'string'),
    ]
)]
class SystemOperationLogUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_type' => 'require',
        'user_id' => 'require',
        'username' => 'require',
        'title' => 'require',
        'controller' => 'require',
        'action' => 'require',
        'method' => 'require',
        'url' => 'require',
        'params' => 'require',
        'status' => 'require',
        'cost_time' => 'require',
        'ip' => 'require',
        'user_agent' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_type.require' => '请设置账户类型:admin=后台,user=前台',
        'user_id.require' => '请设置用户ID',
        'username.require' => '请设置操作人账号快照',
        'title.require' => '请设置操作名称,如:删除用户',
        'controller.require' => '请设置控制器',
        'action.require' => '请设置方法',
        'method.require' => '请设置请求方式',
        'url.require' => '请设置请求地址',
        'params.require' => '请设置请求参数(JSON)',
        'status.require' => '请设置结果:0=失败,1=成功',
        'cost_time.require' => '请设置耗时(毫秒)',
        'ip.require' => '请设置操作IP',
        'user_agent.require' => '请设置User-Agent',
    ];
}
