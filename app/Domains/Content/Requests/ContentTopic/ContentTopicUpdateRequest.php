<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentTopic;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentTopicUpdateRequest',
    required: [
        self::getId,
        self::getTitle,
        self::getSlug,
        self::getCover,
        self::getDescription,
        self::getContent,
        self::getTemplate,
        self::getSeoTitle,
        self::getSeoKeywords,
        self::getSeoDescription,
        self::getStartTime,
        self::getEndTime,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getTitle, description: '专题标题', type: 'string'),
        new OA\Property(property: self::getSlug, description: '专题标识,用于URL', type: 'string'),
        new OA\Property(property: self::getCover, description: '专题头图', type: 'string'),
        new OA\Property(property: self::getDescription, description: '专题简介', type: 'string'),
        new OA\Property(property: self::getContent, description: '自定义内容(富文本)', type: 'string'),
        new OA\Property(property: self::getTemplate, description: '模板标识,空=默认模板', type: 'string'),
        new OA\Property(property: self::getSeoTitle, description: 'SEO标题', type: 'string'),
        new OA\Property(property: self::getSeoKeywords, description: 'SEO关键词', type: 'string'),
        new OA\Property(property: self::getSeoDescription, description: 'SEO描述', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '上线时间,空=立即', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '下线时间,空=不限', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=下线,1=上线', type: 'integer'),
    ]
)]
class ContentTopicUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getTitle = 'title';

    public const string getSlug = 'slug';

    public const string getCover = 'cover';

    public const string getDescription = 'description';

    public const string getContent = 'content';

    public const string getTemplate = 'template';

    public const string getSeoTitle = 'seoTitle';

    public const string getSeoKeywords = 'seoKeywords';

    public const string getSeoDescription = 'seoDescription';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getTitle => 'required',
            self::getSlug => 'required',
            self::getCover => 'required',
            self::getDescription => 'required',
            self::getContent => 'required',
            self::getTemplate => 'required',
            self::getSeoTitle => 'required',
            self::getSeoKeywords => 'required',
            self::getSeoDescription => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getTitle.'.required' => '请设置专题标题',
            self::getSlug.'.required' => '请设置专题标识,用于URL',
            self::getCover.'.required' => '请设置专题头图',
            self::getDescription.'.required' => '请设置专题简介',
            self::getContent.'.required' => '请设置自定义内容(富文本)',
            self::getTemplate.'.required' => '请设置模板标识,空=默认模板',
            self::getSeoTitle.'.required' => '请设置SEO标题',
            self::getSeoKeywords.'.required' => '请设置SEO关键词',
            self::getSeoDescription.'.required' => '请设置SEO描述',
            self::getStartTime.'.required' => '请设置上线时间,空=立即',
            self::getEndTime.'.required' => '请设置下线时间,空=不限',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=下线,1=上线',
        ];
    }
}
