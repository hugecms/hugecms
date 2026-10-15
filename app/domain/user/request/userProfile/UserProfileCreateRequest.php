<?php

declare(strict_types=1);

namespace app\domain\user\request\userProfile;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserProfileCreateRequest',
    required: [
        'user_id',
        'nickname',
        'avatar',
        'gender',
        'birthday',
        'bio',
        'location',
    ],
    properties: [
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'nickname', description: '昵称', type: 'string'),
        new OA\Property(property: 'avatar', description: '头像', type: 'string'),
        new OA\Property(property: 'gender', description: '性别:0=未知,1=男,2=女', type: 'integer'),
        new OA\Property(property: 'birthday', description: '生日', type: 'string'),
        new OA\Property(property: 'bio', description: '个人简介', type: 'string'),
        new OA\Property(property: 'location', description: '所在地', type: 'string'),
    ]
)]
class UserProfileCreateRequest extends Validate
{
    protected $rule = [
        'user_id' => 'require',
        'nickname' => 'require',
        'avatar' => 'require',
        'gender' => 'require',
        'birthday' => 'require',
        'bio' => 'require',
        'location' => 'require',
    ];

    protected $message = [
        'user_id.require' => '请设置用户ID',
        'nickname.require' => '请设置昵称',
        'avatar.require' => '请设置头像',
        'gender.require' => '请设置性别:0=未知,1=男,2=女',
        'birthday.require' => '请设置生日',
        'bio.require' => '请设置个人简介',
        'location.require' => '请设置所在地',
    ];
}
