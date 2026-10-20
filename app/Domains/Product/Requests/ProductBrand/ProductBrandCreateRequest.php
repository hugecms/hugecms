<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductBrand;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductBrandCreateRequest',
    required: [
        self::getName,
        self::getLogo,
        self::getSort,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '品牌名称', type: 'string'),
        new OA\Property(property: self::getLogo, description: '品牌LOGO', type: 'string'),
        new OA\Property(property: self::getSort, description: '排序', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductBrandCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getLogo = 'logo';

    public const string getSort = 'sort';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getLogo => 'required',
            self::getSort => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置品牌名称',
            self::getLogo.'.required' => '请设置品牌LOGO',
            self::getSort.'.required' => '请设置排序',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
