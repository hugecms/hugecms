<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserSocial;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSocialCreateRequest',
    required: [
        self::getUserId,
        self::getPlatform,
        self::getOpenId,
        self::getUnionId,
        self::getNickname,
        self::getAvatar,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getPlatform, description: '平台:wechat,qq,weibo,github', type: 'string'),
        new OA\Property(property: self::getOpenId, description: '第三方OpenID', type: 'string'),
        new OA\Property(property: self::getUnionId, description: '第三方UnionID,如微信多应用互通', type: 'string'),
        new OA\Property(property: self::getNickname, description: '第三方昵称快照', type: 'string'),
        new OA\Property(property: self::getAvatar, description: '第三方头像快照', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class UserSocialCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getPlatform = 'platform';

    public const string getOpenId = 'openId';

    public const string getUnionId = 'unionId';

    public const string getNickname = 'nickname';

    public const string getAvatar = 'avatar';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getPlatform => 'required',
            self::getOpenId => 'required',
            self::getUnionId => 'required',
            self::getNickname => 'required',
            self::getAvatar => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getPlatform.'.required' => '请设置平台:wechat,qq,weibo,github',
            self::getOpenId.'.required' => '请设置第三方OpenID',
            self::getUnionId.'.required' => '请设置第三方UnionID,如微信多应用互通',
            self::getNickname.'.required' => '请设置第三方昵称快照',
            self::getAvatar.'.required' => '请设置第三方头像快照',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
