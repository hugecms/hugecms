<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatFan;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatFanQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '公众号AppID', type: 'string'),
        new OA\Property(property: self::getOpenid, description: '公众号OpenID', type: 'string'),
        new OA\Property(property: self::getUnionid, description: '开放平台UnionID,跨端识别同一用户', type: 'string'),
        new OA\Property(property: self::getUserId, description: '关联注册用户ID,未注册为空', type: 'integer'),
    ]
)]
class WechatFanQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAppid = 'appid';

    public const string getOpenid = 'openid';

    public const string getUnionid = 'unionid';

    public const string getUserId = 'userId';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
