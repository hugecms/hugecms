<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserSocial;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserSocialUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getPlatform,
        self::getOpenId,
        self::getUnionId,
        self::getNickname,
        self::getAvatar,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getPlatform, description: '平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub', type: 'string'),
        new OA\Property(property: self::getOpenId, description: '第三方OpenID', type: 'string'),
        new OA\Property(property: self::getUnionId, description: '第三方UnionID,如微信多应用互通', type: 'string'),
        new OA\Property(property: self::getNickname, description: '第三方昵称快照', type: 'string'),
        new OA\Property(property: self::getAvatar, description: '第三方头像快照', type: 'string'),
    ]
)]
class UserSocialUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getPlatform = 'platform';

    public const string getOpenId = 'openId';

    public const string getUnionId = 'unionId';

    public const string getNickname = 'nickname';

    public const string getAvatar = 'avatar';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getPlatform => 'required',
            self::getOpenId => 'required',
            self::getUnionId => 'required',
            self::getNickname => 'required',
            self::getAvatar => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getPlatform.'.required' => '请设置平台:wechat=微信,qq=QQ,weibo=微博,github=GitHub',
            self::getOpenId.'.required' => '请设置第三方OpenID',
            self::getUnionId.'.required' => '请设置第三方UnionID,如微信多应用互通',
            self::getNickname.'.required' => '请设置第三方昵称快照',
            self::getAvatar.'.required' => '请设置第三方头像快照',
        ];
    }
}
