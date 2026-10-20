<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductAttributeValue;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductAttributeValueQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getAttributeId, description: '属性ID', type: 'integer'),
        new OA\Property(property: self::getValue, description: '属性值', type: 'string'),
    ]
)]
class ProductAttributeValueQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getProductId = 'productId';

    public const string getAttributeId = 'attributeId';

    public const string getValue = 'value';

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
