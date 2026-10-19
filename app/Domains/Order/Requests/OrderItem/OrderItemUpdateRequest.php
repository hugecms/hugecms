<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderItem;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderItemUpdateRequest',
    required: [
        self::getId,
        self::getOrderId,
        self::getProductId,
        self::getSkuId,
        self::getProductName,
        self::getSkuSpecs,
        self::getImage,
        self::getPrice,
        self::getQuantity,
        self::getTotalAmount,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID', type: 'integer'),
        new OA\Property(property: self::getProductName, description: '商品名称快照', type: 'string'),
        new OA\Property(property: self::getSkuSpecs, description: '规格快照', type: 'string'),
        new OA\Property(property: self::getImage, description: '商品图快照', type: 'string'),
        new OA\Property(property: self::getPrice, description: '成交单价', type: 'string'),
        new OA\Property(property: self::getQuantity, description: '数量', type: 'integer'),
        new OA\Property(property: self::getTotalAmount, description: '小计', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
    ]
)]
class OrderItemUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderId = 'orderId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getProductName = 'productName';

    public const string getSkuSpecs = 'skuSpecs';

    public const string getImage = 'image';

    public const string getPrice = 'price';

    public const string getQuantity = 'quantity';

    public const string getTotalAmount = 'totalAmount';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getOrderId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getProductName => 'required',
            self::getSkuSpecs => 'required',
            self::getImage => 'required',
            self::getPrice => 'required',
            self::getQuantity => 'required',
            self::getTotalAmount => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getOrderId.'.required' => '请设置订单ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID',
            self::getProductName.'.required' => '请设置商品名称快照',
            self::getSkuSpecs.'.required' => '请设置规格快照',
            self::getImage.'.required' => '请设置商品图快照',
            self::getPrice.'.required' => '请设置成交单价',
            self::getQuantity.'.required' => '请设置数量',
            self::getTotalAmount.'.required' => '请设置小计',
            self::getCreateTime.'.required' => '请设置',
        ];
    }
}
