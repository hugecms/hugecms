<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\DataArticle;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'DataArticleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getContentId, description: '关联内容主表ID（一对一）', type: 'integer'),
    ]
)]
class DataArticleQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getContentId = 'contentId';

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
