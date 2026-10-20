<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingFlashSaleLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingFlashSaleLogQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getFlashSaleItemId, description: '秒杀商品ID,关联marketing_flash_sale_items', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '关联订单ID,扣减与回补均关联订单', type: 'integer'),
    ]
)]
class MarketingFlashSaleLogQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getFlashSaleItemId = 'flashSaleItemId';

    public const string getOrderId = 'orderId';

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
