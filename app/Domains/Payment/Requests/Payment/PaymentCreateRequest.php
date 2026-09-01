<?php

declare(strict_types=1);

namespace App\Domains\Payment\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PaymentCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class PaymentCreateRequest extends FormRequest
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
