<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticle;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleUpdateRequest',
    required: [
        self::getId,
        self::getCategoryId,
        self::getTitle,
        self::getSlug,
        self::getSummary,
        self::getCover,
        self::getProductIds,
        self::getAuthorId,
        self::getAuthor,
        self::getIsTop,
        self::getIsRecommend,
        self::getStatus,
        self::getViews,
        self::getPublishedAt,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCategoryId, description: '栏目ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '标题', type: 'string'),
        new OA\Property(property: self::getSlug, description: '文章标识,用于URL', type: 'string'),
        new OA\Property(property: self::getSummary, description: '摘要', type: 'string'),
        new OA\Property(property: self::getCover, description: '封面图', type: 'string'),
        new OA\Property(property: self::getProductIds, description: '关联商品ID列表(JSON数组),种草文章挂商品购买入口', type: 'string'),
        new OA\Property(property: self::getAuthorId, description: '作者管理员ID', type: 'integer'),
        new OA\Property(property: self::getAuthor, description: '作者名称快照', type: 'string'),
        new OA\Property(property: self::getIsTop, description: '置顶:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getIsRecommend, description: '推荐:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=草稿,1=发布,2=下架', type: 'integer'),
        new OA\Property(property: self::getViews, description: '浏览量', type: 'integer'),
        new OA\Property(property: self::getPublishedAt, description: '发布时间', type: 'string'),
    ]
)]
class ContentArticleUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCategoryId = 'categoryId';

    public const string getTitle = 'title';

    public const string getSlug = 'slug';

    public const string getSummary = 'summary';

    public const string getCover = 'cover';

    public const string getProductIds = 'productIds';

    public const string getAuthorId = 'authorId';

    public const string getAuthor = 'author';

    public const string getIsTop = 'isTop';

    public const string getIsRecommend = 'isRecommend';

    public const string getStatus = 'status';

    public const string getViews = 'views';

    public const string getPublishedAt = 'publishedAt';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getCategoryId => 'required',
            self::getTitle => 'required',
            self::getSlug => 'required',
            self::getSummary => 'required',
            self::getCover => 'required',
            self::getProductIds => 'required',
            self::getAuthorId => 'required',
            self::getAuthor => 'required',
            self::getIsTop => 'required',
            self::getIsRecommend => 'required',
            self::getStatus => 'required',
            self::getViews => 'required',
            self::getPublishedAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getCategoryId.'.required' => '请设置栏目ID',
            self::getTitle.'.required' => '请设置标题',
            self::getSlug.'.required' => '请设置文章标识,用于URL',
            self::getSummary.'.required' => '请设置摘要',
            self::getCover.'.required' => '请设置封面图',
            self::getProductIds.'.required' => '请设置关联商品ID列表(JSON数组),种草文章挂商品购买入口',
            self::getAuthorId.'.required' => '请设置作者管理员ID',
            self::getAuthor.'.required' => '请设置作者名称快照',
            self::getIsTop.'.required' => '请设置置顶:0=否,1=是',
            self::getIsRecommend.'.required' => '请设置推荐:0=否,1=是',
            self::getStatus.'.required' => '请设置状态:0=草稿,1=发布,2=下架',
            self::getViews.'.required' => '请设置浏览量',
            self::getPublishedAt.'.required' => '请设置发布时间',
        ];
    }
}
