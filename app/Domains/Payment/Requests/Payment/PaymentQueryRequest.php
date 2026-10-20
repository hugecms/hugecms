<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPaymentNo, description: '支付单号', type: 'string'),
        new OA\Property(property: self::getBizType, description: '业务类型:order=订单支付,recharge=余额充值', type: 'string'),
        new OA\Property(property: self::getBizId, description: '业务单据ID,如:订单ID/充值申请ID', type: 'integer'),
        new OA\Property(property: self::getAppid, description: '发起支付的应用AppID,关联system_apps,多端回调定位依据', type: 'string'),
        new OA\Property(property: self::getTradeNo, description: '第三方交易号', type: 'string'),
    ]
)]
class PaymentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPaymentNo = 'paymentNo';

    public const string getBizType = 'bizType';

    public const string getBizId = 'bizId';

    public const string getAppid = 'appid';

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
