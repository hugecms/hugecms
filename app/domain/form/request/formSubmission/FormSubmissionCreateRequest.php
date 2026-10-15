<?php

declare(strict_types=1);

namespace app\domain\form\request\formSubmission;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'FormSubmissionCreateRequest',
    required: [
        'id',
        'form_id',
        'user_id',
        'data',
        'status',
        'ip',
        'user_agent',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'form_id', description: '表单ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '提交用户ID,游客为空', type: 'integer'),
        new OA\Property(property: 'data', description: '提交数据(JSON,{字段标识:值})', type: 'json'),
        new OA\Property(property: 'status', description: '处理状态:0=未处理,1=已处理', type: 'integer'),
        new OA\Property(property: 'ip', description: '提交IP', type: 'string'),
        new OA\Property(property: 'user_agent', description: 'User-Agent', type: 'string'),
    ]
)]
class FormSubmissionCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'form_id' => 'require',
        'user_id' => 'require',
        'data' => 'require',
        'status' => 'require',
        'ip' => 'require',
        'user_agent' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'form_id.require' => '请设置表单ID',
        'user_id.require' => '请设置提交用户ID,游客为空',
        'data.require' => '请设置提交数据(JSON,{字段标识:值})',
        'status.require' => '请设置处理状态:0=未处理,1=已处理',
        'ip.require' => '请设置提交IP',
        'user_agent.require' => '请设置User-Agent',
    ];
}
