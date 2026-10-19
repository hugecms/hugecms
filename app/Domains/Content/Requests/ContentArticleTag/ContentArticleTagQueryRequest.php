<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticleTag;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleTagQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getTagId, description: '标签ID', type: 'integer'),
    ]
)]
class ContentArticleTagQueryRequest extends FormRequest
{
    public const string getTagId = 'tagId';

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
