<?php

declare(strict_types=1);

namespace app\domain\content\request\contentPage;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentPageUpdateRequest',
    required: [
        'id',
        'title',
        'slug',
        'content',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'status',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'title', description: '页面标题', type: 'string'),
        new OA\Property(property: 'slug', description: '页面标识,如:about', type: 'string'),
        new OA\Property(property: 'content', description: '页面内容', type: 'string'),
        new OA\Property(property: 'seo_title', description: 'SEO标题', type: 'string'),
        new OA\Property(property: 'seo_keywords', description: 'SEO关键词', type: 'string'),
        new OA\Property(property: 'seo_description', description: 'SEO描述', type: 'string'),
        new OA\Property(property: 'status', description: '状态:0=隐藏,1=显示', type: 'integer'),
    ]
)]
class ContentPageUpdateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'title' => 'require',
        'slug' => 'require',
        'content' => 'require',
        'seo_title' => 'require',
        'seo_keywords' => 'require',
        'seo_description' => 'require',
        'status' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'title.require' => '请设置页面标题',
        'slug.require' => '请设置页面标识,如:about',
        'content.require' => '请设置页面内容',
        'seo_title.require' => '请设置SEO标题',
        'seo_keywords.require' => '请设置SEO关键词',
        'seo_description.require' => '请设置SEO描述',
        'status.require' => '请设置状态:0=隐藏,1=显示',
    ];
}
