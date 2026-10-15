<?php

declare(strict_types=1);

namespace app\domain\product\request\product;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductCreateRequest',
    required: [
        'id',
        'category_id',
        'brand_id',
        'name',
        'sn',
        'subtitle',
        'cover',
        'images',
        'content',
        'price',
        'market_price',
        'stock',
        'sales',
        'status',
        'sort',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'category_id', description: '分类ID', type: 'integer'),
        new OA\Property(property: 'brand_id', description: '品牌ID', type: 'integer'),
        new OA\Property(property: 'name', description: '商品名称', type: 'string'),
        new OA\Property(property: 'sn', description: '商品编码', type: 'string'),
        new OA\Property(property: 'subtitle', description: '副标题', type: 'string'),
        new OA\Property(property: 'cover', description: '主图', type: 'string'),
        new OA\Property(property: 'images', description: '图册(JSON数组)', type: 'string'),
        new OA\Property(property: 'content', description: '商品详情', type: 'string'),
        new OA\Property(property: 'price', description: '售价,多规格时为最低规格价', type: 'float'),
        new OA\Property(property: 'market_price', description: '市场价', type: 'float'),
        new OA\Property(property: 'stock', description: '库存,单规格商品有效', type: 'integer'),
        new OA\Property(property: 'sales', description: '销量', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=下架,1=上架', type: 'integer'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
    ]
)]
class ProductCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'category_id' => 'require',
        'brand_id' => 'require',
        'name' => 'require',
        'sn' => 'require',
        'subtitle' => 'require',
        'cover' => 'require',
        'images' => 'require',
        'content' => 'require',
        'price' => 'require',
        'market_price' => 'require',
        'stock' => 'require',
        'sales' => 'require',
        'status' => 'require',
        'sort' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'category_id.require' => '请设置分类ID',
        'brand_id.require' => '请设置品牌ID',
        'name.require' => '请设置商品名称',
        'sn.require' => '请设置商品编码',
        'subtitle.require' => '请设置副标题',
        'cover.require' => '请设置主图',
        'images.require' => '请设置图册(JSON数组)',
        'content.require' => '请设置商品详情',
        'price.require' => '请设置售价,多规格时为最低规格价',
        'market_price.require' => '请设置市场价',
        'stock.require' => '请设置库存,单规格商品有效',
        'sales.require' => '请设置销量',
        'status.require' => '请设置状态:0=下架,1=上架',
        'sort.require' => '请设置排序',
    ];
}
