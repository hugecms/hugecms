<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Requests\ShippingArea;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ShippingAreaQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getShippingId, description: '配送方式ID', type: 'integer'),
    ]
)]
class ShippingAreaQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getShippingId = 'shippingId';

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
