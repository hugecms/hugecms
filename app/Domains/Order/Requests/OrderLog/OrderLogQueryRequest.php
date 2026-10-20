<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
    ]
)]
class OrderLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

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
