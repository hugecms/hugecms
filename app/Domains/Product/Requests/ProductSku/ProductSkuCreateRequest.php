<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSkuCreateRequest',
    required: [
        self::getProductId,
        self::getSpecs,
        self::getPrice,
        self::getStock,
        self::getLockedStock,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSpecs, description: '规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}', type: 'string'),
        new OA\Property(property: self::getPrice, description: '售价', type: 'string'),
        new OA\Property(property: self::getStock, description: '库存', type: 'integer'),
        new OA\Property(property: self::getLockedStock, description: '锁定库存(下单未支付),可售=stock-locked_stock', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductSkuCreateRequest extends FormRequest
{
    public const string getProductId = 'productId';

    public const string getSpecs = 'specs';

    public const string getPrice = 'price';

    public const string getStock = 'stock';

    public const string getLockedStock = 'lockedStock';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getProductId => 'required',
            self::getSpecs => 'required',
            self::getPrice => 'required',
            self::getStock => 'required',
            self::getLockedStock => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getProductId.'.required' => '请设置商品ID',
            self::getSpecs.'.required' => '请设置规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}',
            self::getPrice.'.required' => '请设置售价',
            self::getStock.'.required' => '请设置库存',
            self::getLockedStock.'.required' => '请设置锁定库存(下单未支付),可售=stock-locked_stock',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
