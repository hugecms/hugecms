<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductBrand;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductBrandQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '品牌名称', type: 'string'),
    ]
)]
class ProductBrandQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

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
