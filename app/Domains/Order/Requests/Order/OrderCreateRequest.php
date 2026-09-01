<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class OrderCreateRequest extends FormRequest
{
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
