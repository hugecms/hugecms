<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderPromotion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderPromotionQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动', type: 'string'),
        new OA\Property(property: self::getRelatedId, description: '关联ID:coupon=用户券ID,promotion=活动ID,points=空', type: 'integer'),
    ]
)]
class OrderPromotionQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getOrderId = 'orderId';

    public const string getType = 'type';

    public const string getRelatedId = 'relatedId';

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
