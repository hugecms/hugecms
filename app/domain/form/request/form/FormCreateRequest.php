<?php

declare(strict_types=1);

namespace app\domain\form\request\form;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'FormCreateRequest',
    required: [
        'id',
        'name',
        'description',
        'success_message',
        'start_time',
        'end_time',
        'need_login',
        'max_submits',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '表单名称,如:活动报名', type: 'string'),
        new OA\Property(property: 'description', description: '表单描述', type: 'string'),
        new OA\Property(property: 'success_message', description: '提交成功提示语', type: 'string'),
        new OA\Property(property: 'start_time', description: '可提交开始时间,空=不限', type: 'string'),
        new OA\Property(property: 'end_time', description: '可提交结束时间,空=不限', type: 'string'),
        new OA\Property(property: 'need_login', description: '需要登录:0=否,1=是', type: 'integer'),
        new OA\Property(property: 'max_submits', description: '每人限提次数,0=不限', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class FormCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'description' => 'require',
        'success_message' => 'require',
        'start_time' => 'require',
        'end_time' => 'require',
        'need_login' => 'require',
        'max_submits' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'name.require' => '请设置表单名称,如:活动报名',
        'description.require' => '请设置表单描述',
        'success_message.require' => '请设置提交成功提示语',
        'start_time.require' => '请设置可提交开始时间,空=不限',
        'end_time.require' => '请设置可提交结束时间,空=不限',
        'need_login.require' => '请设置需要登录:0=否,1=是',
        'max_submits.require' => '请设置每人限提次数,0=不限',
        'status.require' => '请设置状态:0=停用,1=启用',
    ];
}
