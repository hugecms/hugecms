<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductStockReminder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductStockReminderUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getProductId,
        self::getSkuId,
        self::getStatus,
        self::getNotifiedAt,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '登记用户ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,0=不限规格', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待提醒,1=已提醒,2=已取消', type: 'integer'),
        new OA\Property(property: self::getNotifiedAt, description: '提醒时间', type: 'string'),
    ]
)]
class ProductStockReminderUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

    public const string getStatus = 'status';

    public const string getNotifiedAt = 'notifiedAt';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getProductId => 'required',
            self::getSkuId => 'required',
            self::getStatus => 'required',
            self::getNotifiedAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置登记用户ID',
            self::getProductId.'.required' => '请设置商品ID',
            self::getSkuId.'.required' => '请设置SKU ID,0=不限规格',
            self::getStatus.'.required' => '请设置状态:0=待提醒,1=已提醒,2=已取消',
            self::getNotifiedAt.'.required' => '请设置提醒时间',
        ];
    }
}
