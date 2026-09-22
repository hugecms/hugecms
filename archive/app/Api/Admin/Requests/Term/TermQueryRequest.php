<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Term;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TermQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getSlug, description: '分类项别名（URL友好）', type: 'string'),
        new OA\Property(property: self::getParentId, description: '父级ID（0代表顶级）', type: 'integer'),
    ]
)]
class TermQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getSlug = 'slug';

    public const string getParentId = 'parentId';

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
