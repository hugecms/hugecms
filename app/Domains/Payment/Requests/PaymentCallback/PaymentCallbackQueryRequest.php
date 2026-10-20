<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\PaymentCallback;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentCallbackQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPaymentId, description: '关联支付单ID,未匹配到支付单时为空', type: 'integer'),
        new OA\Property(property: self::getChannel, description: '渠道:wechat,alipay', type: 'string'),
        new OA\Property(property: self::getNotifyId, description: '第三方通知唯一ID,幂等去重依据', type: 'string'),
        new OA\Property(property: self::getTradeNo, description: '第三方交易号', type: 'string'),
    ]
)]
class PaymentCallbackQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPaymentId = 'paymentId';

    public const string getChannel = 'channel';

    public const string getNotifyId = 'notifyId';

    public const string getTradeNo = 'tradeNo';

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
