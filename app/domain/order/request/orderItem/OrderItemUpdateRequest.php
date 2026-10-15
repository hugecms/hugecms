<?php

declare(strict_types=1);

namespace app\domain\order\request\orderItem;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderItemUpdateRequest',
    required: [
        'id',
        'order_id',
        'product_id',
        'sku_id',
        'product_name',
        'sku_specs',
        'image',
        'price',
        'quantity',
        'total_amount',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'order_id', description: '订单ID', type: 'integer'),
        new OA\Property(property: 'product_id', description: '商品ID', type: 'integer'),
        new OA\Property(property: 'sku_id', description: 'SKU ID', type: 'integer'),
        new OA\Property(property: 'product_name', description: '商品名称快照', type: 'string'),
        new OA\Property(property: 'sku_specs', description: '规格快照', type: 'string'),
        new OA\Property(property: 'image', description: '商品图快照', type: 'string'),
        new OA\Property(property: 'price', description: '成交单价', type: 'float'),
        new OA\Property(property: 'quantity', description: '数量', type: 'integer'),
        new OA\Property(property: 'total_amount', description: '小计', type: 'float'),
    ]
)]
class OrderItemUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'order_id' => 'require',
        'product_id' => 'require',
        'sku_id' => 'require',
        'product_name' => 'require',
        'sku_specs' => 'require',
        'image' => 'require',
        'price' => 'require',
        'quantity' => 'require',
        'total_amount' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'order_id.require' => '请设置订单ID',
        'product_id.require' => '请设置商品ID',
        'sku_id.require' => '请设置SKU ID',
        'product_name.require' => '请设置商品名称快照',
        'sku_specs.require' => '请设置规格快照',
        'image.require' => '请设置商品图快照',
        'price.require' => '请设置成交单价',
        'quantity.require' => '请设置数量',
        'total_amount.require' => '请设置小计',
    ];
}
