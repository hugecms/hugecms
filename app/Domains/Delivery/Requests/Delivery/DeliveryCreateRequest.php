<?php

declare(strict_types=1);

namespace App\Domains\Delivery\Requests\Delivery;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'DeliveryCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class DeliveryCreateRequest extends FormRequest
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
