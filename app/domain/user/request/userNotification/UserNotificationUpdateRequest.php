<?php

declare(strict_types=1);

namespace app\domain\user\request\userNotification;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserNotificationUpdateRequest',
    required: [
        'id',
        'user_id',
        'type',
        'title',
        'content',
        'params',
        'read_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '接收用户ID', type: 'integer'),
        new OA\Property(property: 'type', description: '类型:order,comment,system', type: 'string'),
        new OA\Property(property: 'title', description: '通知标题', type: 'string'),
        new OA\Property(property: 'content', description: '通知内容', type: 'string'),
        new OA\Property(property: 'params', description: '跳转参数(JSON)', type: 'string'),
        new OA\Property(property: 'read_at', description: '已读时间,空=未读', type: 'string'),
    ]
)]
class UserNotificationUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'type' => 'require',
        'title' => 'require',
        'content' => 'require',
        'params' => 'require',
        'read_at' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置接收用户ID',
        'type.require' => '请设置类型:order,comment,system',
        'title.require' => '请设置通知标题',
        'content.require' => '请设置通知内容',
        'params.require' => '请设置跳转参数(JSON)',
        'read_at.require' => '请设置已读时间,空=未读',
    ];
}
