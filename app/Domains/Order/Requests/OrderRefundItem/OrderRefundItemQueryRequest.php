<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefundItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundItemQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundId, description: '退款单ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID', type: 'integer'),
    ]
)]
class OrderRefundItemQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundId = 'refundId';

    public const string getOrderItemId = 'orderItemId';

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
