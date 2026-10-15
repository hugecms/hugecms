<?php

declare(strict_types=1);

namespace app\domain\content\request\contentCategory;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentCategoryCreateRequest',
    required: [
        'id',
        'parent_id',
        'name',
        'slug',
        'description',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'sort',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'parent_id', description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: 'name', description: '栏目名称', type: 'string'),
        new OA\Property(property: 'slug', description: '栏目标识,用于URL', type: 'string'),
        new OA\Property(property: 'description', description: '栏目描述', type: 'string'),
        new OA\Property(property: 'seo_title', description: 'SEO标题', type: 'string'),
        new OA\Property(property: 'seo_keywords', description: 'SEO关键词', type: 'string'),
        new OA\Property(property: 'seo_description', description: 'SEO描述', type: 'string'),
        new OA\Property(property: 'sort', description: '排序', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer'),
    ]
)]
class ContentCategoryCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'parent_id' => 'require',
        'name' => 'require',
        'slug' => 'require',
        'description' => 'require',
        'seo_title' => 'require',
        'seo_keywords' => 'require',
        'seo_description' => 'require',
        'sort' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'parent_id.require' => '请设置父级ID,0=顶级',
        'name.require' => '请设置栏目名称',
        'slug.require' => '请设置栏目标识,用于URL',
        'description.require' => '请设置栏目描述',
        'seo_title.require' => '请设置SEO标题',
        'seo_keywords.require' => '请设置SEO关键词',
        'seo_description.require' => '请设置SEO描述',
        'sort.require' => '请设置排序',
        'status.require' => '请设置状态:0=隐藏,1=显示',
    ];
}
