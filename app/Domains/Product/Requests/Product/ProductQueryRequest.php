<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCategoryId, description: '分类ID', type: 'integer'),
        new OA\Property(property: self::getBrandId, description: '品牌ID', type: 'integer'),
        new OA\Property(property: self::getSn, description: '商品编码', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=下架,1=上架', type: 'integer'),
    ]
)]
class ProductQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCategoryId = 'categoryId';

    public const string getBrandId = 'brandId';

    public const string getSn = 'sn';

    public const string getStatus = 'status';

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
