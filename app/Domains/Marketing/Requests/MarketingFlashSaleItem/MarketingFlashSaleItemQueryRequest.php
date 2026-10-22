<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingFlashSaleItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingFlashSaleItemQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSaleId, description: '秒杀活动ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,关联product_skus', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=下架,1=上架', type: 'integer'),
    ]
)]
class MarketingFlashSaleItemQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSaleId = 'saleId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getStatus = 'status';

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
