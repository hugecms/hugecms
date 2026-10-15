<?php

declare(strict_types=1);

namespace app\domain\product\request\productSku;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductSkuCreateRequest',
    required: [
        'id',
        'product_id',
        'specs',
        'price',
        'stock',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'product_id', description: '商品ID', type: 'integer'),
        new OA\Property(property: 'specs', description: '规格值(JSON),如:{"颜色":"红","尺码":"L"}', type: 'string'),
        new OA\Property(property: 'price', description: '售价', type: 'float'),
        new OA\Property(property: 'stock', description: '库存', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductSkuCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'product_id' => 'require',
        'specs' => 'require',
        'price' => 'require',
        'stock' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'product_id.require' => '请设置商品ID',
        'specs.require' => '请设置规格值(JSON),如:{"颜色":"红","尺码":"L"}',
        'price.require' => '请设置售价',
        'stock.require' => '请设置库存',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
