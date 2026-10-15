<?php

declare(strict_types=1);

namespace app\domain\order\request\orderCart;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'OrderCartCreateRequest',
    required: [
        'id',
        'user_id',
        'product_id',
        'sku_id',
        'product_name',
        'image',
        'price',
        'quantity',
        'checked',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'user_id', description: '用户ID', type: 'integer'),
        new OA\Property(property: 'product_id', description: '商品ID', type: 'integer'),
        new OA\Property(property: 'sku_id', description: 'SKU ID,0=单规格', type: 'integer'),
        new OA\Property(property: 'product_name', description: '商品名称快照', type: 'string'),
        new OA\Property(property: 'image', description: '商品图快照', type: 'string'),
        new OA\Property(property: 'price', description: '加购时价格快照', type: 'float'),
        new OA\Property(property: 'quantity', description: '数量', type: 'integer'),
        new OA\Property(property: 'checked', description: '结算勾选:0=否,1=是', type: 'integer'),
    ]
)]
class OrderCartCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'user_id' => 'require',
        'product_id' => 'require',
        'sku_id' => 'require',
        'product_name' => 'require',
        'image' => 'require',
        'price' => 'require',
        'quantity' => 'require',
        'checked' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'user_id.require' => '请设置用户ID',
        'product_id.require' => '请设置商品ID',
        'sku_id.require' => '请设置SKU ID,0=单规格',
        'product_name.require' => '请设置商品名称快照',
        'image.require' => '请设置商品图快照',
        'price.require' => '请设置加购时价格快照',
        'quantity.require' => '请设置数量',
        'checked.require' => '请设置结算勾选:0=否,1=是',
    ];
}
