<?php

declare(strict_types=1);

namespace App\Domains\Cart\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CartCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class CartCreateRequest extends FormRequest
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
