<?php

declare(strict_types=1);

namespace app\domain\user\request\userSocial;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'UserSocialCreateRequest',
    required: [
        'id',
        'user_id',
        'platform',
        'open_id',
        'union_id',
        'nickname',
        'avatar',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'platform', description: '平台:wechat,qq,weibo,github', type: 'string'),
        new OA\Property(property: 'open_id', description: '第三方OpenID', type: 'string'),
        new OA\Property(property: 'union_id', description: '第三方UnionID,如微信多应用互通', type: 'string'),
        new OA\Property(property: 'nickname', description: '第三方昵称快照', type: 'string'),
        new OA\Property(property: 'avatar', description: '第三方头像快照', type: 'string'),
    ]
)]
class UserSocialCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'platform' => 'require',
        'open_id' => 'require',
        'union_id' => 'require',
        'nickname' => 'require',
        'avatar' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'platform.require' => '请设置平台:wechat,qq,weibo,github',
        'open_id.require' => '请设置第三方OpenID',
        'union_id.require' => '请设置第三方UnionID,如微信多应用互通',
        'nickname.require' => '请设置第三方昵称快照',
        'avatar.require' => '请设置第三方头像快照',
    ];
}
