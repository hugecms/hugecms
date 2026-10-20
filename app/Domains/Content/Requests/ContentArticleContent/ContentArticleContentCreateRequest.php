<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticleContent;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleContentCreateRequest',
    required: [
        self::getArticleId,
        self::getContent,
    ],
    properties: [
        new OA\Property(property: self::getArticleId, description: '文章ID', type: 'integer'),
        new OA\Property(property: self::getContent, description: '正文(富文本)', type: 'string'),
    ]
)]
class ContentArticleContentCreateRequest extends FormRequest
{
    public const string getArticleId = 'articleId';

    public const string getContent = 'content';

    public function rules(): array
    {
        return [
            self::getArticleId => 'required',
            self::getContent => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getArticleId.'.required' => '请设置文章ID',
            self::getContent.'.required' => '请设置正文(富文本)',
        ];
    }
}
