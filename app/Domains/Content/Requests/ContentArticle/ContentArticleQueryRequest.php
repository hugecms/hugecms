<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentArticle;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentArticleQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCategoryId, description: '栏目ID', type: 'integer'),
        new OA\Property(property: self::getSlug, description: '文章标识,用于URL', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=草稿,1=发布,2=下架', type: 'integer'),
        new OA\Property(property: self::getPublishedAt, description: '发布时间', type: 'string'),
    ]
)]
class ContentArticleQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCategoryId = 'categoryId';

    public const string getSlug = 'slug';

    public const string getStatus = 'status';

    public const string getPublishedAt = 'publishedAt';

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
