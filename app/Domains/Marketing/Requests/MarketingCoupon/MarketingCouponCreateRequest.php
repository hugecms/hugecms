<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingCoupon;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingCouponCreateRequest',
    required: [
        self::getName,
        self::getType,
        self::getMinAmount,
        self::getReduceAmount,
        self::getDiscountRate,
        self::getReceiveType,
        self::getTotal,
        self::getPerLimit,
        self::getStartTime,
        self::getEndTime,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '券名称', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券', type: 'string'),
        new OA\Property(property: self::getMinAmount, description: '使用门槛,0=无门槛', type: 'string'),
        new OA\Property(property: self::getReduceAmount, description: '满减金额,满减券必填', type: 'string'),
        new OA\Property(property: self::getDiscountRate, description: '折扣率,如8.5表示85折,折扣券必填', type: 'string'),
        new OA\Property(property: self::getReceiveType, description: '发放方式:manual=后台发放,order=下单赠送,register=注册赠送', type: 'string'),
        new OA\Property(property: self::getTotal, description: '发放总量,0=不限', type: 'integer'),
        new OA\Property(property: self::getPerLimit, description: '每人限领', type: 'integer'),
        new OA\Property(property: self::getStartTime, description: '有效期开始,空=领取即生效', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '有效期结束', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingCouponCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getType = 'type';

    public const string getMinAmount = 'minAmount';

    public const string getReduceAmount = 'reduceAmount';

    public const string getDiscountRate = 'discountRate';

    public const string getReceiveType = 'receiveType';

    public const string getTotal = 'total';

    public const string getPerLimit = 'perLimit';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getType => 'required',
            self::getMinAmount => 'required',
            self::getReduceAmount => 'required',
            self::getDiscountRate => 'required',
            self::getReceiveType => 'required',
            self::getTotal => 'required',
            self::getPerLimit => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置券名称',
            self::getType.'.required' => '请设置类型:full_reduce=满减券,discount=折扣券,free_shipping=免邮券',
            self::getMinAmount.'.required' => '请设置使用门槛,0=无门槛',
            self::getReduceAmount.'.required' => '请设置满减金额,满减券必填',
            self::getDiscountRate.'.required' => '请设置折扣率,如8.5表示85折,折扣券必填',
            self::getReceiveType.'.required' => '请设置发放方式:manual=后台发放,order=下单赠送,register=注册赠送',
            self::getTotal.'.required' => '请设置发放总量,0=不限',
            self::getPerLimit.'.required' => '请设置每人限领',
            self::getStartTime.'.required' => '请设置有效期开始,空=领取即生效',
            self::getEndTime.'.required' => '请设置有效期结束',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
