<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefundLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundId, description: '退款单ID', type: 'integer'),
    ]
)]
class OrderRefundLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundId = 'refundId';

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
