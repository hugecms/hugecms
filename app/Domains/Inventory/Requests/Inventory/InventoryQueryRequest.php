<?php

declare(strict_types=1);

namespace App\Domains\Inventory\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'InventoryQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
    ]
)]
class InventoryQueryRequest extends FormRequest
{
    public const string getId = 'id';

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
