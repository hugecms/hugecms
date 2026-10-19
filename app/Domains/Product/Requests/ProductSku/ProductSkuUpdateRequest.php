<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductSku;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductSkuUpdateRequest',
    required: [
        self::getId,
        self::getProductId,
        self::getSpecs,
        self::getPrice,
        self::getStock,
        self::getStatus,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSpecs, description: '规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}', type: 'string'),
        new OA\Property(property: self::getPrice, description: '售价', type: 'string'),
        new OA\Property(property: self::getStock, description: '库存', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class ProductSkuUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getProductId = 'productId';

    public const string getSpecs = 'specs';

    public const string getPrice = 'price';

    public const string getStock = 'stock';

    public const string getStatus = 'status';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getProductId => 'required',
            self::getSpecs => 'required',
            self::getPrice => 'required',
            self::getStock => 'required',
            self::getStatus => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSpecs.'.required' => '请设置规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}',
            self::getPrice.'.required' => '请设置售价',
            self::getStock.'.required' => '请设置库存',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
