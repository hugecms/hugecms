<?php

declare(strict_types=1);

namespace App\Domains\Order\Requests\OrderPromotion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderPromotionCreateRequest',
    required: [
        self::getOrderId,
        self::getType,
        self::getRelatedId,
        self::getName,
        self::getOrderItemId,
        self::getAmount,
        self::getPoints,
    ],
    properties: [
        new OA\Property(property: self::getOrderId, description: '订单ID', type: 'integer'),
        new OA\Property(property: self::getType, description: '优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动', type: 'string'),
        new OA\Property(property: self::getRelatedId, description: '关联ID:coupon=用户券ID,promotion=活动ID,points=空', type: 'integer'),
        new OA\Property(property: self::getName, description: '优惠名称快照,如:满100减20', type: 'string'),
        new OA\Property(property: self::getOrderItemId, description: '订单明细ID,空=全单优惠', type: 'integer'),
        new OA\Property(property: self::getAmount, description: '抵扣金额', type: 'string'),
        new OA\Property(property: self::getPoints, description: '消耗积分,积分抵扣时记录,退款按比例返还', type: 'integer'),
    ]
)]
class OrderPromotionCreateRequest extends FormRequest
{
    public const string getOrderId = 'orderId';

    public const string getType = 'type';

    public const string getRelatedId = 'relatedId';

    public const string getName = 'name';

    public const string getOrderItemId = 'orderItemId';

    public const string getAmount = 'amount';

    public const string getPoints = 'points';

    public function rules(): array
    {
        return [
            self::getOrderId => 'required',
            self::getType => 'required',
            self::getRelatedId => 'required',
            self::getName => 'required',
            self::getOrderItemId => 'required',
            self::getAmount => 'required',
            self::getPoints => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getOrderId.'.required' => '请设置订单ID',
            self::getType.'.required' => '请设置优惠类型:coupon=优惠券,points=积分抵扣,promotion=促销活动',
            self::getRelatedId.'.required' => '请设置关联ID:coupon=用户券ID,promotion=活动ID,points=空',
            self::getName.'.required' => '请设置优惠名称快照,如:满100减20',
            self::getOrderItemId.'.required' => '请设置订单明细ID,空=全单优惠',
            self::getAmount.'.required' => '请设置抵扣金额',
            self::getPoints.'.required' => '请设置消耗积分,积分抵扣时记录,退款按比例返还',
        ];
    }
}
