<?php

declare(strict_types=1);

namespace App\Domains\Product\Requests\ProductStockReminder;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductStockReminderQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '登记用户ID', type: 'integer'),
        new OA\Property(property: self::getProductId, description: '商品ID', type: 'integer'),
        new OA\Property(property: self::getSkuId, description: 'SKU ID,0=不限规格', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=待提醒,1=已提醒,2=已取消', type: 'integer'),
    ]
)]
class ProductStockReminderQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getProductId = 'productId';

    public const string getSkuId = 'skuId';

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
