<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticleTag;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleTagCreateRequest',
    required: [
        self::getArticleId,
        self::getTagId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getArticleId, description: '文章ID', type: 'integer'),
        new OA\Property(property: self::getTagId, description: '标签ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class ContentArticleTagCreateRequest extends FormRequest
{
    public const string getArticleId = 'articleId';

    public const string getTagId = 'tagId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getArticleId => 'required',
            self::getTagId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getArticleId.'.required' => '请设置文章ID',
            self::getTagId.'.required' => '请设置标签ID',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
