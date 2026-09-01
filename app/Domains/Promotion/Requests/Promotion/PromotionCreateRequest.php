<?php

declare(strict_types=1);

namespace App\Domains\Promotion\Requests\Promotion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PromotionCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class PromotionCreateRequest extends FormRequest
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
