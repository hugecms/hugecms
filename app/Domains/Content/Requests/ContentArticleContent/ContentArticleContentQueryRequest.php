<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticleContent;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleContentQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getArticleId, description: '文章ID', type: 'integer'),
    ]
)]
class ContentArticleContentQueryRequest extends FormRequest
{
    public const string getArticleId = 'articleId';

    public function rules(): array
    {
        return [
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
