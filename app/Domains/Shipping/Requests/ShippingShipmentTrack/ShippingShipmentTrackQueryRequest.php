<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Requests\ShippingShipmentTrack;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingShipmentTrackQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getShipmentId, description: '发货单ID', type: 'integer'),
        new OA\Property(property: self::getAcceptTime, description: '轨迹发生时间', type: 'string'),
    ]
)]
class ShippingShipmentTrackQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getShipmentId = 'shipmentId';

    public const string getAcceptTime = 'acceptTime';

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
