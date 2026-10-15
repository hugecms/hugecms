<?php

declare(strict_types=1);

namespace app\domain\content\request\contentArticle;

use OpenApi\Attributes as OA;
use think\Validate;

#[OA\Schema(
    schema: 'ContentArticleCreateRequest',
    required: [
        'id',
        'category_id',
        'title',
        'slug',
        'summary',
        'cover',
        'author_id',
        'author',
        'is_top',
        'is_recommend',
        'status',
        'views',
        'published_at',
    ],
    properties: [
        new OA\Property(property: 'id', description: 'ID', type: 'integer'),
        new OA\Property(property: 'category_id', description: '栏目ID', type: 'integer'),
        new OA\Property(property: 'title', description: '标题', type: 'string'),
        new OA\Property(property: 'slug', description: '文章标识,用于URL', type: 'string'),
        new OA\Property(property: 'summary', description: '摘要', type: 'string'),
        new OA\Property(property: 'cover', description: '封面图', type: 'string'),
        new OA\Property(property: 'author_id', description: '作者管理员ID', type: 'integer'),
        new OA\Property(property: 'author', description: '作者名称快照', type: 'string'),
        new OA\Property(property: 'is_top', description: '置顶:0=否,1=是', type: 'integer'),
        new OA\Property(property: 'is_recommend', description: '推荐:0=否,1=是', type: 'integer'),
        new OA\Property(property: 'status', description: '状态:0=草稿,1=发布,2=下架', type: 'integer'),
        new OA\Property(property: 'views', description: '浏览量', type: 'integer'),
        new OA\Property(property: 'published_at', description: '发布时间', type: 'string'),
    ]
)]
class ContentArticleCreateRequest extends Validate
{
    protected $rule = [
        'id' => 'require',
        'category_id' => 'require',
        'title' => 'require',
        'slug' => 'require',
        'summary' => 'require',
        'cover' => 'require',
        'author_id' => 'require',
        'author' => 'require',
        'is_top' => 'require',
        'is_recommend' => 'require',
        'status' => 'require',
        'views' => 'require',
        'published_at' => 'require',
    ];

    protected $message = [
        'id.require' => '请设置ID',
        'category_id.require' => '请设置栏目ID',
        'title.require' => '请设置标题',
        'slug.require' => '请设置文章标识,用于URL',
        'summary.require' => '请设置摘要',
        'cover.require' => '请设置封面图',
        'author_id.require' => '请设置作者管理员ID',
        'author.require' => '请设置作者名称快照',
        'is_top.require' => '请设置置顶:0=否,1=是',
        'is_recommend.require' => '请设置推荐:0=否,1=是',
        'status.require' => '请设置状态:0=草稿,1=发布,2=下架',
        'views.require' => '请设置浏览量',
        'published_at.require' => '请设置发布时间',
    ];
}
