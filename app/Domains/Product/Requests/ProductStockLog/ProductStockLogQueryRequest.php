<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductStockLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductStockLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,0=单规格商品', type: 'integer'),
        new OA\Property(property: self::getRelatedId, description: '关联单据ID(订单/退款单)', type: 'integer'),
    ]
)]
class ProductStockLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSkuId = 'skuId';

    public const string getRelatedId = 'relatedId';

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
