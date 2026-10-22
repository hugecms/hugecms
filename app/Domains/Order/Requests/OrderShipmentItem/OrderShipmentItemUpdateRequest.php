<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderShipmentItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderShipmentItemUpdateRequest',
    required: [
        self::getId,
        self::getShipmentId,
        self::getOrderItemId,
        self::getProductId,
        self::getSkuId,
        self::getQuantity,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getShipmentId, description: '发货单ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,关联product_skus', type: 'integer'),
        new OA\Property(property: self::getQuantity, description: '发货数量', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class OrderShipmentItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getShipmentId = 'shipmentId';

    public const string getOrderItemId = 'orderItemId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getQuantity = 'quantity';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getShipmentId => 'required',
            self::getOrderItemId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getQuantity => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getShipmentId.'.required' => '请设置发货单ID',
            self::getOrderItemId.'.required' => '请设置订单明细ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,关联product_skus',
            self::getQuantity.'.required' => '请设置发货数量',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
