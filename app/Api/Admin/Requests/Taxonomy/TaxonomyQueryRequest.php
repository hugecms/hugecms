<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Taxonomy;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaxonomyQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getKeyword, description: '关键词模糊搜索', type: 'string'),
        new OA\Property(property: self::getAlias, description: '分类法别名（如：article_cat）', type: 'string'),
        new OA\Property(property: self::getModelId, description: '绑定的模型ID（NULL表示全局分类）', type: 'integer'),
    ]
)]
class TaxonomyQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getKeyword = 'keyword';

    public const string getAlias = 'alias';

    public const string getModelId = 'modelId';

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
