<?php

declare(strict_types=1);

namespace app\domain\product\request\productBrand;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductBrandCreateRequest',
    required: [
        'id',
        'name',
        'logo',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'name', description: '品牌名称', type: 'string'),
        new OA\Property(property: 'logo', description: '品牌LOGO', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductBrandCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'name' => 'require',
        'logo' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'name.require' => '请设置品牌名称',
        'logo.require' => '请设置品牌LOGO',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
