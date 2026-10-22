<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductStockLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductStockLogCreateRequest',
    required: [
        self::getProductId,
        self::getSkuId,
        self::getChangeType,
        self::getChangeQuantity,
        self::getBeforeStock,
        self::getAfterStock,
        self::getRelatedId,
        self::getUserType,
        self::getUserId,
        self::getRemark,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,关联product_skus', type: 'integer'),
        new OA\Property(property: self::getChangeType, description: '变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整', type: 'string'),
        new OA\Property(property: self::getChangeQuantity, description: '变动数量,正数入库负数出库', type: 'integer'),
        new OA\Property(property: self::getBeforeStock, description: '变动前库存', type: 'integer'),
        new OA\Property(property: self::getAfterStock, description: '变动后库存', type: 'integer'),
        new OA\Property(property: self::getRelatedId, description: '关联单据ID(订单/退款单)', type: 'integer'),
        new OA\Property(property: self::getUserType, description: '操作账户类型:admin=后台,user=前台,system=系统', type: 'string'),
        new OA\Property(property: self::getUserId, description: '操作人ID', type: 'integer'),
        new OA\Property(property: self::getRemark, description: '备注', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class ProductStockLogCreateRequest extends FormRequest
{
    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getChangeType = 'changeType';

    public const string getChangeQuantity = 'changeQuantity';

    public const string getBeforeStock = 'beforeStock';

    public const string getAfterStock = 'afterStock';

    public const string getRelatedId = 'relatedId';

    public const string getUserType = 'userType';

    public const string getUserId = 'userId';

    public const string getRemark = 'remark';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getChangeType => 'required',
            self::getChangeQuantity => 'required',
            self::getBeforeStock => 'required',
            self::getAfterStock => 'required',
            self::getRelatedId => 'required',
            self::getUserType => 'required',
            self::getUserId => 'required',
            self::getRemark => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,关联product_skus',
            self::getChangeType.'.required' => '请设置变动类型:order=下单占用,cancel=取消释放,refund=退货回补,adjust=人工调整',
            self::getChangeQuantity.'.required' => '请设置变动数量,正数入库负数出库',
            self::getBeforeStock.'.required' => '请设置变动前库存',
            self::getAfterStock.'.required' => '请设置变动后库存',
            self::getRelatedId.'.required' => '请设置关联单据ID(订单/退款单)',
            self::getUserType.'.required' => '请设置操作账户类型:admin=后台,user=前台,system=系统',
            self::getUserId.'.required' => '请设置操作人ID',
            self::getRemark.'.required' => '请设置备注',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
