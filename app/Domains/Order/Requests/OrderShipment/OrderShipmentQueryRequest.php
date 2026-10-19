<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderShipment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderShipmentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getExpressNo, description: '运单号', type: 'string'),
    ]
)]
class OrderShipmentQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderId = 'orderId';

    public const string getExpressNo = 'expressNo';

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
