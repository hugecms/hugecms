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
        self::getSkuNo,
        self::getBarcode,
        self::getImage,
        self::getPrice,
        self::getLinePrice,
        self::getCostPrice,
        self::getWeight,
        self::getStock,
        self::getWarnStock,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSpecs, description: '规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}', type: 'string'),
        new OA\Property(property: self::getSkuNo, description: 'SKU编号', type: 'string'),
        new OA\Property(property: self::getBarcode, description: '条码,仓储/扫码出入库', type: 'string'),
        new OA\Property(property: self::getImage, description: 'SKU图片', type: 'string'),
        new OA\Property(property: self::getPrice, description: '售价', type: 'string'),
        new OA\Property(property: self::getLinePrice, description: '划线价(市场价展示)', type: 'string'),
        new OA\Property(property: self::getCostPrice, description: '成本价,毛利核算', type: 'string'),
        new OA\Property(property: self::getWeight, description: '重量(kg),运费计算', type: 'string'),
        new OA\Property(property: self::getStock, description: '库存', type: 'integer'),
        new OA\Property(property: self::getWarnStock, description: '库存预警线,库存&lt;=该值触发预警,0=不预警', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=禁用,1=启用', type: 'integer'),
    ]
)]
class ProductSkuUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getProductId = 'productId';

    public const string getSpecs = 'specs';

    public const string getSkuNo = 'skuNo';

    public const string getBarcode = 'barcode';

    public const string getImage = 'image';

    public const string getPrice = 'price';

    public const string getLinePrice = 'linePrice';

    public const string getCostPrice = 'costPrice';

    public const string getWeight = 'weight';

    public const string getStock = 'stock';

    public const string getWarnStock = 'warnStock';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getProductId => 'required',
            self::getSpecs => 'required',
            self::getSkuNo => 'required',
            self::getBarcode => 'required',
            self::getImage => 'required',
            self::getPrice => 'required',
            self::getLinePrice => 'required',
            self::getCostPrice => 'required',
            self::getWeight => 'required',
            self::getStock => 'required',
            self::getWarnStock => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSpecs.'.required' => '请设置规格值(JSON),如:{&quot;颜色&quot;:&quot;红&quot;,&quot;尺码&quot;:&quot;L&quot;}',
            self::getSkuNo.'.required' => '请设置SKU编号',
            self::getBarcode.'.required' => '请设置条码,仓储/扫码出入库',
            self::getImage.'.required' => '请设置SKU图片',
            self::getPrice.'.required' => '请设置售价',
            self::getLinePrice.'.required' => '请设置划线价(市场价展示)',
            self::getCostPrice.'.required' => '请设置成本价,毛利核算',
            self::getWeight.'.required' => '请设置重量(kg),运费计算',
            self::getStock.'.required' => '请设置库存',
            self::getWarnStock.'.required' => '请设置库存预警线,库存&lt;=该值触发预警,0=不预警',
            self::getStatus.'.required' => '请设置状态:0=禁用,1=启用',
        ];
    }
}
