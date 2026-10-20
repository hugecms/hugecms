<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderRefundItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRefundItemUpdateRequest',
    required: [
        self::getId,
        self::getRefundId,
        self::getOrderItemId,
        self::getProductId,
        self::getSkuId,
        self::getQuantity,
        self::getAmount,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getRefundId, description: '退款单ID', type: 'integer'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,0=单规格', type: 'integer'),
        new OA\Property(property: self::getQuantity, description: '退货数量', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '退款金额', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class OrderRefundItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getRefundId = 'refundId';

    public const string getOrderItemId = 'orderItemId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getQuantity = 'quantity';

    public const string getAmount = 'amount';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getRefundId => 'required',
            self::getOrderItemId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getQuantity => 'required',
            self::getAmount => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getRefundId.'.required' => '请设置退款单ID',
            self::getOrderItemId.'.required' => '请设置订单明细ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,0=单规格',
            self::getQuantity.'.required' => '请设置退货数量',
            self::getAmount.'.required' => '请设置退款金额',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
