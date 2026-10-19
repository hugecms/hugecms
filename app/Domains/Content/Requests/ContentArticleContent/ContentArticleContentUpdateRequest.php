<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticleContent;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleContentUpdateRequest',
    required: [
        self::getArticleId,
        self::getContent,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getArticleId, description: '文章ID', type: 'integer'),
        new OA\Property(property: self::getContent, description: '正文(富文本)', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class ContentArticleContentUpdateRequest extends FormRequest
{
    public const string getArticleId = 'articleId';

    public const string getContent = 'content';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getArticleId => 'required',
            self::getContent => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getArticleId.'.required' => '请设置文章ID',
            self::getContent.'.required' => '请设置正文(富文本)',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
