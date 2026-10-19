<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentPage;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentPageCreateRequest',
    required: [
        self::getTitle,
        self::getSlug,
        self::getContent,
        self::getSeoTitle,
        self::getSeoKeywords,
        self::getSeoDescription,
        self::getStatus,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getTitle, description: '页面标题', type: 'string'),
        new OA\Property(property: self::getSlug, description: '页面标识,如:about', type: 'string'),
        new OA\Property(property: self::getContent, description: '页面内容', type: 'string'),
        new OA\Property(property: self::getSeoTitle, description: 'SEO标题', type: 'string'),
        new OA\Property(property: self::getSeoKeywords, description: 'SEO关键词', type: 'string'),
        new OA\Property(property: self::getSeoDescription, description: 'SEO描述', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=隐藏,1=显示', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class ContentPageCreateRequest extends FormRequest
{
    public const string getTitle = 'title';

    public const string getSlug = 'slug';

    public const string getContent = 'content';

    public const string getSeoTitle = 'seoTitle';

    public const string getSeoKeywords = 'seoKeywords';

    public const string getSeoDescription = 'seoDescription';

    public const string getStatus = 'status';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getTitle => 'required',
            self::getSlug => 'required',
            self::getContent => 'required',
            self::getSeoTitle => 'required',
            self::getSeoKeywords => 'required',
            self::getSeoDescription => 'required',
            self::getStatus => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getTitle.'.required' => '请设置页面标题',
            self::getSlug.'.required' => '请设置页面标识,如:about',
            self::getContent.'.required' => '请设置页面内容',
            self::getSeoTitle.'.required' => '请设置SEO标题',
            self::getSeoKeywords.'.required' => '请设置SEO关键词',
            self::getSeoDescription.'.required' => '请设置SEO描述',
            self::getStatus.'.required' => '请设置状态:0=隐藏,1=显示',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
