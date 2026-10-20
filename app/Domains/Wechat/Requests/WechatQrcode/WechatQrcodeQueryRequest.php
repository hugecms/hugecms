<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatQrcode;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatQrcodeQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '二维码所属应用AppID,公众号带参码或小程序码', type: 'string'),
        new OA\Property(property: self::getSceneStr, description: '场景值,同一应用下唯一', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:distributor=分销邀请,product=商品推广,channel=渠道投放', type: 'string'),
        new OA\Property(property: self::getTargetId, description: '绑定目标ID,分销码=分销员用户ID,商品码=商品ID', type: 'integer'),
    ]
)]
class WechatQrcodeQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAppid = 'appid';

    public const string getSceneStr = 'sceneStr';

    public const string getType = 'type';

    public const string getTargetId = 'targetId';

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
