<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserAccountLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserAccountLogCreateRequest',
    required: [
        self::getUserId,
        self::getChangeType,
        self::getChangeAmount,
        self::getChangePoints,
        self::getDescription,
        self::getRelatedId,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getChangeType, description: '变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整', type: 'string'),
        new OA\Property(property: self::getChangeAmount, description: '资金变动,正数入负数出', type: 'string'),
        new OA\Property(property: self::getChangePoints, description: '积分变动,正数入负数出', type: 'integer'),
        new OA\Property(property: self::getDescription, description: '变动说明', type: 'string'),
        new OA\Property(property: self::getRelatedId, description: '关联单据ID', type: 'integer'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserAccountLogCreateRequest extends FormRequest
{
    public const string getUserId = 'userId';

    public const string getChangeType = 'changeType';

    public const string getChangeAmount = 'changeAmount';

    public const string getChangePoints = 'changePoints';

    public const string getDescription = 'description';

    public const string getRelatedId = 'relatedId';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getUserId => 'required',
            self::getChangeType => 'required',
            self::getChangeAmount => 'required',
            self::getChangePoints => 'required',
            self::getDescription => 'required',
            self::getRelatedId => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getUserId.'.required' => '请设置用户ID',
            self::getChangeType.'.required' => '请设置变动类型:recharge=充值,withdraw=提现,order_pay=订单消费,refund=退款返现,points_exchange=积分兑换,admin=人工调整',
            self::getChangeAmount.'.required' => '请设置资金变动,正数入负数出',
            self::getChangePoints.'.required' => '请设置积分变动,正数入负数出',
            self::getDescription.'.required' => '请设置变动说明',
            self::getRelatedId.'.required' => '请设置关联单据ID',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
