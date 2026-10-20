<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingUserCoupon;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingUserCouponQueryRequest',
    required: [],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getCouponId, description: '优惠券类型ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '持有用户ID,发放即创建归属', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=未使用,1=已使用,2=已过期', type: 'integer'),
    ]
)]
class MarketingUserCouponQueryRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getCouponId = 'couponId';

    public const string getUserId = 'userId';

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
