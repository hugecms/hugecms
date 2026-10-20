<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingFlashSaleLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingFlashSaleLogUpdateRequest',
    required: [
        self::getId,
        self::getFlashSaleItemId,
        self::getOrderId,
        self::getChangeQuantity,
        self::getBeforeSoldStock,
        self::getAfterSoldStock,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFlashSaleItemId, description: '秒杀商品ID,关联marketing_flash_sale_items', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '关联订单ID,扣减与回补均关联订单', type: 'integer'),
        new OA\Property(property: self::getChangeQuantity, description: '变动数量,正数扣减,负数回补', type: 'integer'),
        new OA\Property(property: self::getBeforeSoldStock, description: '变动前已售量', type: 'integer'),
        new OA\Property(property: self::getAfterSoldStock, description: '变动后已售量', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class MarketingFlashSaleLogUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getFlashSaleItemId = 'flashSaleItemId';

    public const string getOrderId = 'orderId';

    public const string getChangeQuantity = 'changeQuantity';

    public const string getBeforeSoldStock = 'beforeSoldStock';

    public const string getAfterSoldStock = 'afterSoldStock';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getFlashSaleItemId => 'required',
            self::getOrderId => 'required',
            self::getChangeQuantity => 'required',
            self::getBeforeSoldStock => 'required',
            self::getAfterSoldStock => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getFlashSaleItemId.'.required' => '请设置秒杀商品ID,关联marketing_flash_sale_items',
            self::getOrderId.'.required' => '请设置关联订单ID,扣减与回补均关联订单',
            self::getChangeQuantity.'.required' => '请设置变动数量,正数扣减,负数回补',
            self::getBeforeSoldStock.'.required' => '请设置变动前已售量',
            self::getAfterSoldStock.'.required' => '请设置变动后已售量',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
