<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductCategoryUpdateRequest',
    required: [
        self::getId,
        self::getParentId,
        self::getName,
        self::getSort,
        self::getStatus,
        self::getIsRecommend,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getParentId, description: '父级ID,0=顶级', type: 'integer'),
        new OA\Property(property: self::getName, description: '分类名称', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
        new OA\Property(property: self::getIsRecommend, description: '首页推荐:0=否,1=是', type: 'integer'),
    ]
)]
class ProductCategoryUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getParentId = 'parentId';

    public const string getName = 'name';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public const string getIsRecommend = 'isRecommend';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getParentId => 'required',
            self::getName => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
            self::getIsRecommend => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getParentId.'.required' => '请设置父级ID,0=顶级',
            self::getName.'.required' => '请设置分类名称',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
            self::getIsRecommend.'.required' => '请设置首页推荐:0=否,1=是',
        ];
    }
}
