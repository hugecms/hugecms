<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductAttribute;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductAttributeQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTypeId, description: '所属商品类型ID', type: 'integer'),
    ]
)]
class ProductAttributeQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTypeId = 'typeId';

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
