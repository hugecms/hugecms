<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'InventoryCreateRequest',
    required: [
    ],
    properties: [
    ]
)]
class InventoryCreateRequest extends FormRequest
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
