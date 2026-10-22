<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSkuQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuNo, description: 'SKU编号', type: 'string'),
        new OA\Property(property: self::getBarcode, description: '条码,仓储/扫码出入库', type: 'string'),
    ]
)]
class ProductSkuQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getProductId = 'productId';

    public const string getSkuNo = 'skuNo';

    public const string getBarcode = 'barcode';

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
