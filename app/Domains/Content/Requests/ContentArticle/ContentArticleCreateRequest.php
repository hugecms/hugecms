<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticle;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleCreateRequest',
    required: [
        self::getCategoryId,
        self::getTitle,
        self::getSlug,
        self::getSummary,
        self::getCover,
        self::getAuthorId,
        self::getAuthor,
        self::getIsTop,
        self::getIsRecommend,
        self::getStatus,
        self::getViews,
        self::getPublishedAt,
        self::getCreateTime,
        self::getUpdateTime,
        self::getDeleteTime,
    ],
    properties: [
        new OA\Property(property: self::getCategoryId, description: '栏目ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '标题', type: 'string'),
        new OA\Property(property: self::getSlug, description: '文章标识,用于URL', type: 'string'),
        new OA\Property(property: self::getSummary, description: '摘要', type: 'string'),
        new OA\Property(property: self::getCover, description: '封面图', type: 'string'),
        new OA\Property(property: self::getAuthorId, description: '作者管理员ID', type: 'integer'),
        new OA\Property(property: self::getAuthor, description: '作者名称快照', type: 'string'),
        new OA\Property(property: self::getIsTop, description: '置顶:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getIsRecommend, description: '推荐:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=草稿,1=发布,2=下架', type: 'integer'),
        new OA\Property(property: self::getViews, description: '浏览量', type: 'integer'),
        new OA\Property(property: self::getPublishedAt, description: '发布时间', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
        new OA\Property(property: self::getDeleteTime, description: '', type: 'string'),
    ]
)]
class ContentArticleCreateRequest extends FormRequest
{
    public const string getCategoryId = 'categoryId';

    public const string getTitle = 'title';

    public const string getSlug = 'slug';

    public const string getSummary = 'summary';

    public const string getCover = 'cover';

    public const string getAuthorId = 'authorId';

    public const string getAuthor = 'author';

    public const string getIsTop = 'isTop';

    public const string getIsRecommend = 'isRecommend';

    public const string getStatus = 'status';

    public const string getViews = 'views';

    public const string getPublishedAt = 'publishedAt';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public const string getDeleteTime = 'deleteTime';

    public function rules(): array
    {
        return [
            self::getCategoryId => 'required',
            self::getTitle => 'required',
            self::getSlug => 'required',
            self::getSummary => 'required',
            self::getCover => 'required',
            self::getAuthorId => 'required',
            self::getAuthor => 'required',
            self::getIsTop => 'required',
            self::getIsRecommend => 'required',
            self::getStatus => 'required',
            self::getViews => 'required',
            self::getPublishedAt => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
            self::getDeleteTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCategoryId.'.required' => '请设置栏目ID',
            self::getTitle.'.required' => '请设置标题',
            self::getSlug.'.required' => '请设置文章标识,用于URL',
            self::getSummary.'.required' => '请设置摘要',
            self::getCover.'.required' => '请设置封面图',
            self::getAuthorId.'.required' => '请设置作者管理员ID',
            self::getAuthor.'.required' => '请设置作者名称快照',
            self::getIsTop.'.required' => '请设置置顶:0=否,1=是',
            self::getIsRecommend.'.required' => '请设置推荐:0=否,1=是',
            self::getStatus.'.required' => '请设置状态:0=草稿,1=发布,2=下架',
            self::getViews.'.required' => '请设置浏览量',
            self::getPublishedAt.'.required' => '请设置发布时间',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
            self::getDeleteTime.'.required' => '请设置',
        ];
    }
}
