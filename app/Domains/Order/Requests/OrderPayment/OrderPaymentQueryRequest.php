<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderPayment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderPaymentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getPaymentNo, description: '支付单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getTradeNo, description: '第三方交易号', type: 'string'),
    ]
)]
class OrderPaymentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getPaymentNo = 'paymentNo';

    public const string getOrderId = 'orderId';

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
