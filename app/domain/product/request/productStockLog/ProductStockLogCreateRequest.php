<?php

declare(strict_types=1);

namespace app\domain\product\request\productStockLog;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductStockLogCreateRequest',
    required: [
        'id',
        'product_id',
        'sku_id',
        'change_type',
        'change_quantity',
        'before_stock',
        'after_stock',
        'related_id',
        'user_type',
        'user_id',
        'remark',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'product_id', description: '商品ID', type: 'integer'),
        new OA\Property(property: 'sku_id', description: 'SKU ID,0=单规格商品', type: 'integer'),
        new OA\Property(property: 'change_type', description: '变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整', type: 'string'),
        new OA\Property(property: 'change_quantity', description: '变动数量,正数入库负数出库', type: 'integer'),
        new OA\Property(property: 'before_stock', description: '变动前库存', type: 'integer'),
        new OA\Property(property: 'after_stock', description: '变动后库存', type: 'integer'),
        new OA\Property(property: 'related_id', description: '关联单据ID(订单/退款单)', type: 'integer'),
        new OA\Property(property: 'user_type', description: '操作账户类型:admin,user,system', type: 'string'),
        new OA\Property(property: 'user_id', description: '操作人ID', type: 'integer'),
        new OA\Property(property: 'remark', description: '备注', type: 'string'),
    ]
)]
class ProductStockLogCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'product_id' => 'require',
        'sku_id' => 'require',
        'change_type' => 'require',
        'change_quantity' => 'require',
        'before_stock' => 'require',
        'after_stock' => 'require',
        'related_id' => 'require',
        'user_type' => 'require',
        'user_id' => 'require',
        'remark' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'product_id.require' => '请设置商品ID',
        'sku_id.require' => '请设置SKU ID,0=单规格商品',
        'change_type.require' => '请设置变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整',
        'change_quantity.require' => '请设置变动数量,正数入库负数出库',
        'before_stock.require' => '请设置变动前库存',
        'after_stock.require' => '请设置变动后库存',
        'related_id.require' => '请设置关联单据ID(订单/退款单)',
        'user_type.require' => '请设置操作账户类型:admin,user,system',
        'user_id.require' => '请设置操作人ID',
        'remark.require' => '请设置备注',
    ];
}
