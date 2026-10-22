<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\PaymentChannel;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentChannelQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCode, description: '渠道标识:wechat=微信支付,alipay=支付宝,balance=余额', type: 'string'),
        new OA\Property(property: self::getAppid, description: '应用AppID,空=通用配置,非空=该应用专属,同渠道不同应用可配不同商户号', type: 'string'),
    ]
)]
class PaymentChannelQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCode = 'code';

    public const string getAppid = 'appid';

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
