<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests\Taxonomy;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaxonomyUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getAlias,
        self::getModelId,
        self::getIsHierarchical,
        self::getDescription,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '分类法名称（如：文章分类、产品系列）', type: 'string'),
        new OA\Property(property: self::getAlias, description: '分类法别名（如：article_cat）', type: 'string'),
        new OA\Property(property: self::getModelId, description: '绑定的模型ID（NULL表示全局分类）', type: 'integer'),
        new OA\Property(property: self::getIsHierarchical, description: '是否支持层级：1是（分类目录），0否（标签）', type: 'integer'),
        new OA\Property(property: self::getDescription, description: '描述', type: 'string'),
    ]
)]
class TaxonomyUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getAlias = 'alias';

    public const string getModelId = 'modelId';

    public const string getIsHierarchical = 'isHierarchical';

    public const string getDescription = 'description';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getAlias => 'required',
            self::getModelId => 'required',
            self::getIsHierarchical => 'required',
            self::getDescription => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置分类法名称（如：文章分类、产品系列）',
            self::getAlias.'.required' => '请设置分类法别名（如：article_cat）',
            self::getModelId.'.required' => '请设置绑定的模型ID（NULL表示全局分类）',
            self::getIsHierarchical.'.required' => '请设置是否支持层级：1是（分类目录），0否（标签）',
            self::getDescription.'.required' => '请设置描述',
        ];
    }
}
