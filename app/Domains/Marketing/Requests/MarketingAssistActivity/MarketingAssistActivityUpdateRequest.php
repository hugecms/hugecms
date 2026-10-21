<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingAssistActivity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingAssistActivityUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getRewardType,
        self::getRewardRef,
        self::getRequiredCount,
        self::getPerLimit,
        self::getStartTime,
        self::getEndTime,
        self::getConfig,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getRewardType, description: '奖励类型:1=优惠券,2=积分', type: 'integer'),
        new OA\Property(property: self::getRewardRef, description: '奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config', type: 'integer'),
        new OA\Property(property: self::getRequiredCount, description: '所需助力人数', type: 'integer'),
        new OA\Property(property: self::getPerLimit, description: '每人可发起次数', type: 'integer'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'string'),
        new OA\Property(property: self::getConfig, description: '扩展参数(积分数/阶梯奖励等)', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingAssistActivityUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getRewardType = 'rewardType';

    public const string getRewardRef = 'rewardRef';

    public const string getRequiredCount = 'requiredCount';

    public const string getPerLimit = 'perLimit';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getConfig = 'config';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getRewardType => 'required',
            self::getRewardRef => 'required',
            self::getRequiredCount => 'required',
            self::getPerLimit => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getConfig => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置活动名称',
            self::getRewardType.'.required' => '请设置奖励类型:1=优惠券,2=积分',
            self::getRewardRef.'.required' => '请设置奖励载体ID:券类型ID(marketing_coupons);积分场景存点数于config',
            self::getRequiredCount.'.required' => '请设置所需助力人数',
            self::getPerLimit.'.required' => '请设置每人可发起次数',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getConfig.'.required' => '请设置扩展参数(积分数/阶梯奖励等)',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
