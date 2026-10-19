<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentCategoryCreateRequest',
    required: [
        self::getParentId,
        self::getName,
        self::getSlug,
        self::getDescription,
        self::getSeoTitle,
        self::getSeoKeywords,
        self::getSeoDescription,
        self::getSort,
        self::getStatus,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getParentId, description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: self::getName, description: '栏目名称', type: 'string'),
        new OA\Property(property: self::getSlug, description: '栏目标识,用于URL', type: 'string'),
        new OA\Property(property: self::getDescription, description: '栏目描述', type: 'string'),
        new OA\Property(property: self::getSeoTitle, description: 'SEO标题', type: 'string'),
        new OA\Property(property: self::getSeoKeywords, description: 'SEO关键词', type: 'string'),
        new OA\Property(property: self::getSeoDescription, description: 'SEO描述', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=隐藏,1=显示', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class ContentCategoryCreateRequest extends FormRequest
{
    public const string getParentId = 'parentId';

    public const string getName = 'name';

    public const string getSlug = 'slug';

    public const string getDescription = 'description';

    public const string getSeoTitle = 'seoTitle';

    public const string getSeoKeywords = 'seoKeywords';

    public const string getSeoDescription = 'seoDescription';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getParentId => 'required',
            self::getName => 'required',
            self::getSlug => 'required',
            self::getDescription => 'required',
            self::getSeoTitle => 'required',
            self::getSeoKeywords => 'required',
            self::getSeoDescription => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getParentId.'.required' => '请设置父级ID,0=顶级',
            self::getName.'.required' => '请设置栏目名称',
            self::getSlug.'.required' => '请设置栏目标识,用于URL',
            self::getDescription.'.required' => '请设置栏目描述',
            self::getSeoTitle.'.required' => '请设置SEO标题',
            self::getSeoKeywords.'.required' => '请设置SEO关键词',
            self::getSeoDescription.'.required' => '请设置SEO描述',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=隐藏,1=显示',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
