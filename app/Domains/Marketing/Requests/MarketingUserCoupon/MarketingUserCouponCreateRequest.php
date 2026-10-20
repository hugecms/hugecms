<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingUserCoupon;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingUserCouponCreateRequest',
    required: [
        self::getCouponId,
        self::getUserId,
        self::getOrderId,
        self::getStatus,
        self::getUsedAt,
        self::getExpireAt,
    ],
    properties: [
        new OA\Property(property: self::getCouponId, description: '优惠券类型ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '持有用户ID,发放即创建归属', type: 'integer'),
        new OA\Property(property: self::getOrderId, description: '核销订单ID', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=未使用,1=已使用,2=已过期', type: 'integer'),
        new OA\Property(property: self::getUsedAt, description: '核销时间', type: 'string'),
        new OA\Property(property: self::getExpireAt, description: '过期时间,空=按券类型有效期', type: 'string'),
    ]
)]
class MarketingUserCouponCreateRequest extends FormRequest
{
    public const string getCouponId = 'couponId';

    public const string getUserId = 'userId';

    public const string getOrderId = 'orderId';

    public const string getStatus = 'status';

    public const string getUsedAt = 'usedAt';

    public const string getExpireAt = 'expireAt';

    public function rules(): array
    {
        return [
            self::getCouponId => 'required',
            self::getUserId => 'required',
            self::getOrderId => 'required',
            self::getStatus => 'required',
            self::getUsedAt => 'required',
            self::getExpireAt => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getCouponId.'.required' => '请设置优惠券类型ID',
            self::getUserId.'.required' => '请设置持有用户ID,发放即创建归属',
            self::getOrderId.'.required' => '请设置核销订单ID',
            self::getStatus.'.required' => '请设置状态:0=未使用,1=已使用,2=已过期',
            self::getUsedAt.'.required' => '请设置核销时间',
            self::getExpireAt.'.required' => '请设置过期时间,空=按券类型有效期',
        ];
    }
}
