<?php

declare(strict_types=1);

namespace App\Domains\User\Requests\UserPointLog;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UserPointLogUpdateRequest',
    required: [
        self::getId,
        self::getUserId,
        self::getBizType,
        self::getChangePoints,
        self::getPointsAfter,
        self::getBizNo,
        self::getDescription,
        self::getCreateTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '用户ID', type: 'integer'),
        new OA\Property(property: self::getBizType, description: '业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送,8=活动奖励(助力等)', type: 'integer'),
        new OA\Property(property: self::getChangePoints, description: '积分变动,正数入负数出', type: 'integer'),
        new OA\Property(property: self::getPointsAfter, description: '变动后积分余额快照', type: 'integer'),
        new OA\Property(property: self::getBizNo, description: '关联业务单号', type: 'string'),
        new OA\Property(property: self::getDescription, description: '变动说明', type: 'string'),
        new OA\Property(property: self::getCreateTime, description: '创建时间', type: 'string'),
    ]
)]
class UserPointLogUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getUserId = 'userId';

    public const string getBizType = 'bizType';

    public const string getChangePoints = 'changePoints';

    public const string getPointsAfter = 'pointsAfter';

    public const string getBizNo = 'bizNo';

    public const string getDescription = 'description';

    public const string getCreateTime = 'createTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getUserId => 'required',
            self::getBizType => 'required',
            self::getChangePoints => 'required',
            self::getPointsAfter => 'required',
            self::getBizNo => 'required',
            self::getDescription => 'required',
            self::getCreateTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getUserId.'.required' => '请设置用户ID',
            self::getBizType.'.required' => '请设置业务类型:1=签到奖励,2=下单抵扣,3=订单积分奖励,4=退款返还,5=积分兑换,6=人工调整,7=注册赠送,8=活动奖励(助力等)',
            self::getChangePoints.'.required' => '请设置积分变动,正数入负数出',
            self::getPointsAfter.'.required' => '请设置变动后积分余额快照',
            self::getBizNo.'.required' => '请设置关联业务单号',
            self::getDescription.'.required' => '请设置变动说明',
            self::getCreateTime.'.required' => '请设置创建时间',
        ];
    }
}
