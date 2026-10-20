<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderShipmentItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderShipmentItemQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getShipmentId, description: '发货单ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID', type: 'integer'),
    ]
)]
class OrderShipmentItemQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getShipmentId = 'shipmentId';

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
