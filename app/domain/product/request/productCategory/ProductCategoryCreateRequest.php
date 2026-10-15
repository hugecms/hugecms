<?php

declare(strict_types=1);

namespace app\domain\product\request\productCategory;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ProductCategoryCreateRequest',
    required: [
        'id',
        'parent_id',
        'name',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'parent_id', description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: 'name', description: '分类名称', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductCategoryCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'parent_id' => 'require',
        'name' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'parent_id.require' => '请设置父级ID,0=顶级',
        'name.require' => '请设置分类名称',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=禁用,1=启用',
    ];
}
