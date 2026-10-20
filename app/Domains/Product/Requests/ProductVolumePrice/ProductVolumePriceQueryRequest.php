<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductVolumePrice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductVolumePriceQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,0=商品统一价', type: 'integer'),
        new OA\Property(property: self::getMinQuantity, description: '起购数量', type: 'integer'),
    ]
)]
class ProductVolumePriceQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getMinQuantity = 'minQuantity';

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
