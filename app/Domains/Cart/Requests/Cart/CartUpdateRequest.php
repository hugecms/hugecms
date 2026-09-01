<?php

declare(strict_types=1);

namespace App\Domains\Cart\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CartUpdateRequest',
    required: [
        self::getId,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
    ]
)]
class CartUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public function rules(): array
    {
        return [
            self::getId => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
        ];
    }
}
