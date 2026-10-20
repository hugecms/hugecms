<?php

declare(strict_types=1);

namespace App\Domains\Wechat\Requests\WechatTemplate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'WechatTemplateQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '所属应用AppID,公众号或小程序', type: 'string'),
        new OA\Property(property: self::getScene, description: '业务场景:order_paid=支付成功,shipped=发货,refund_done=退款完成,coupon_expire=券到期,commission_settled=佣金到账等', type: 'string'),
        new OA\Property(property: self::getType, description: '模板类型:1=公众号模板消息,2=小程序订阅消息', type: 'integer'),
    ]
)]
class WechatTemplateQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getAppid = 'appid';

    public const string getScene = 'scene';

    public const string getType = 'type';

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
