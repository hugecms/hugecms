<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductAttributeValue;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductAttributeValueUpdateRequest',
    required: [
        self::getId,
        self::getProductId,
        self::getAttributeId,
        self::getValue,
        self::getSkuId,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getAttributeId, description: '属性ID', type: 'integer'),
        new OA\Property(property: self::getValue, description: '属性值', type: 'string'),
        new OA\Property(property: self::getSkuId, description: '关联SKU ID,规格属性时对应', type: 'integer'),
    ]
)]
class ProductAttributeValueUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getProductId = 'productId';

    public const string getAttributeId = 'attributeId';

    public const string getValue = 'value';

    public const string getSkuId = 'skuId';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getProductId => 'required',
            self::getAttributeId => 'required',
            self::getValue => 'required',
            self::getSkuId => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getAttributeId.'.required' => '请设置属性ID',
            self::getValue.'.required' => '请设置属性值',
            self::getSkuId.'.required' => '请设置关联SKU ID,规格属性时对应',
        ];
    }
}
