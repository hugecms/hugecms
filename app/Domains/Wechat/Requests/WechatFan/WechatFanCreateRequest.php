<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatFan;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatFanCreateRequest',
    required: [
        self::getAppid,
        self::getOpenid,
        self::getUnionid,
        self::getNickname,
        self::getAvatar,
        self::getSubscribed,
        self::getSubscribedAt,
        self::getUnsubscribedAt,
        self::getSubscribeScene,
        self::getTags,
        self::getUserId,
    ],
    properties: [
        new OA\Property(property: self::getAppid, description: '公众号AppID', type: 'string'),
        new OA\Property(property: self::getOpenid, description: '公众号OpenID', type: 'string'),
        new OA\Property(property: self::getUnionid, description: '开放平台UnionID,跨端识别同一用户', type: 'string'),
        new OA\Property(property: self::getNickname, description: '昵称快照', type: 'string'),
        new OA\Property(property: self::getAvatar, description: '头像快照', type: 'string'),
        new OA\Property(property: self::getSubscribed, description: '关注状态:0=已取关,1=已关注', type: 'integer'),
        new OA\Property(property: self::getSubscribedAt, description: '关注时间', type: 'string'),
        new OA\Property(property: self::getUnsubscribedAt, description: '取关时间', type: 'string'),
        new OA\Property(property: self::getSubscribeScene, description: '关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码', type: 'string'),
        new OA\Property(property: self::getTags, description: '标签ID列表(JSON),群发圈人依据', type: 'string'),
        new OA\Property(property: self::getUserId, description: '关联注册用户ID,未注册为空', type: 'integer'),
    ]
)]
class WechatFanCreateRequest extends FormRequest
{
    public const string getAppid = 'appid';

    public const string getOpenid = 'openid';

    public const string getUnionid = 'unionid';

    public const string getNickname = 'nickname';

    public const string getAvatar = 'avatar';

    public const string getSubscribed = 'subscribed';

    public const string getSubscribedAt = 'subscribedAt';

    public const string getUnsubscribedAt = 'unsubscribedAt';

    public const string getSubscribeScene = 'subscribeScene';

    public const string getTags = 'tags';

    public const string getUserId = 'userId';

    public function rules(): array
    {
        return [
            self::getAppid => 'required',
            self::getOpenid => 'required',
            self::getUnionid => 'required',
            self::getNickname => 'required',
            self::getAvatar => 'required',
            self::getSubscribed => 'required',
            self::getSubscribedAt => 'required',
            self::getUnsubscribedAt => 'required',
            self::getSubscribeScene => 'required',
            self::getTags => 'required',
            self::getUserId => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getAppid.'.required' => '请设置公众号AppID',
            self::getOpenid.'.required' => '请设置公众号OpenID',
            self::getUnionid.'.required' => '请设置开放平台UnionID,跨端识别同一用户',
            self::getNickname.'.required' => '请设置昵称快照',
            self::getAvatar.'.required' => '请设置头像快照',
            self::getSubscribed.'.required' => '请设置关注状态:0=已取关,1=已关注',
            self::getSubscribedAt.'.required' => '请设置关注时间',
            self::getUnsubscribedAt.'.required' => '请设置取关时间',
            self::getSubscribeScene.'.required' => '请设置关注来源场景,如:ADD_SCENE_QR_CODE=带参二维码',
            self::getTags.'.required' => '请设置标签ID列表(JSON),群发圈人依据',
            self::getUserId.'.required' => '请设置关联注册用户ID,未注册为空',
        ];
    }
}
