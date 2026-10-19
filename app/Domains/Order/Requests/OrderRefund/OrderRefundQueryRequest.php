<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefund;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundNo, description: '退款单号', type: 'string'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
    ]
)]
class OrderRefundQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundNo = 'refundNo';

    public const string getOrderId = 'orderId';

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
