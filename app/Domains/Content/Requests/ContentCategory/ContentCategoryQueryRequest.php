<?php

declare(strict_types=1);

namespace App\Domains\Content\Requests\ContentCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContentCategoryQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: self::getSlug, description: '栏目标识,用于URL', type: 'string'),
    ]
)]
class ContentCategoryQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getSlug = 'slug';

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
