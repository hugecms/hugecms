<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductVolumePrice;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductVolumePriceCreateRequest',
    required: [
        self::getProductId,
        self::getSkuId,
        self::getMinQuantity,
        self::getPrice,
    ],
    properties: [
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,0=商品统一价', type: 'integer'),
        new OA\Property(property: self::getMinQuantity, description: '起购数量', type: 'integer'),
        new OA\Property(property: self::getPrice, description: '单价', type: 'string'),
    ]
)]
class ProductVolumePriceCreateRequest extends FormRequest
{
    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getMinQuantity = 'minQuantity';

    public const string getPrice = 'price';

    public function rules(): array
    {
        return [
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getMinQuantity => 'required',
            self::getPrice => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,0=商品统一价',
            self::getMinQuantity.'.required' => '请设置起购数量',
            self::getPrice.'.required' => '请设置单价',
        ];
    }
}
