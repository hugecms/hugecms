<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingFlashSaleItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingFlashSaleItemUpdateRequest',
    required: [
        self::getId,
        self::getSaleId,
        self::getProductId,
        self::getSkuId,
        self::getOriginalPrice,
        self::getPrice,
        self::getTotalStock,
        self::getSoldStock,
        self::getPerLimit,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getSaleId, description: '秒杀活动ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,关联product_skus', type: 'integer'),
        new OA\Property(property: self::getOriginalPrice, description: '原价快照', type: 'string'),
        new OA\Property(property: self::getPrice, description: '秒杀价', type: 'string'),
        new OA\Property(property: self::getTotalStock, description: '秒杀总量', type: 'integer'),
        new OA\Property(property: self::getSoldStock, description: '已售量,可购=total_stock-sold_stock', type: 'integer'),
        new OA\Property(property: self::getPerLimit, description: '每人限购', type: 'integer'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=下架,1=上架', type: 'integer'),
    ]
)]
class MarketingFlashSaleItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getSaleId = 'saleId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getOriginalPrice = 'originalPrice';

    public const string getPrice = 'price';

    public const string getTotalStock = 'totalStock';

    public const string getSoldStock = 'soldStock';

    public const string getPerLimit = 'perLimit';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getSaleId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getOriginalPrice => 'required',
            self::getPrice => 'required',
            self::getTotalStock => 'required',
            self::getSoldStock => 'required',
            self::getPerLimit => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getSaleId.'.required' => '请设置秒杀活动ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,关联product_skus',
            self::getOriginalPrice.'.required' => '请设置原价快照',
            self::getPrice.'.required' => '请设置秒杀价',
            self::getTotalStock.'.required' => '请设置秒杀总量',
            self::getSoldStock.'.required' => '请设置已售量,可购=total_stock-sold_stock',
            self::getPerLimit.'.required' => '请设置每人限购',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=下架,1=上架',
        ];
    }
}
