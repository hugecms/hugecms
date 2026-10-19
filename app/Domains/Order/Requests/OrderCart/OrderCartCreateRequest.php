<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderCart;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderCartCreateRequest',
    required: [
        self::getUserId,
        self::getProductId,
        self::getSkuId,
        self::getProductName,
        self::getImage,
        self::getPrice,
        self::getQuantity,
        self::getChecked,
        self::getCreateTime,
        self::getUpdateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,0=单规格', type: 'integer'),
        new OA\Property(property: self::getProductName, description: '商品名称快照', type: 'string'),
        new OA\Property(property: self::getImage, description: '商品图快照', type: 'string'),
        new OA\Property(property: self::getPrice, description: '加购时价格快照', type: 'string'),
        new OA\Property(property: self::getQuantity, description: '数量', type: 'integer'),
        new OA\Property(property: self::getChecked, description: '结算勾选:0=否,1=是', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '', type: 'string'),
        new OA\Property(property: self::getUpdateTime, description: '', type: 'string'),
    ]
)]
class OrderCartCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getProductName = 'productName';

    public const string getImage = 'image';

    public const string getPrice = 'price';

    public const string getQuantity = 'quantity';

    public const string getChecked = 'checked';

    public const string getCreateTime = 'createTime';

    public const string getUpdateTime = 'updateTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getProductName => 'required',
            self::getImage => 'required',
            self::getPrice => 'required',
            self::getQuantity => 'required',
            self::getChecked => 'required',
            self::getCreateTime => 'required',
            self::getUpdateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,0=单规格',
            self::getProductName.'.required' => '请设置商品名称快照',
            self::getImage.'.required' => '请设置商品图快照',
            self::getPrice.'.required' => '请设置加购时价格快照',
            self::getQuantity.'.required' => '请设置数量',
            self::getChecked.'.required' => '请设置结算勾选:0=否,1=是',
            self::getCreateTime.'.required' => '请设置',
            self::getUpdateTime.'.required' => '请设置',
        ];
    }
}
