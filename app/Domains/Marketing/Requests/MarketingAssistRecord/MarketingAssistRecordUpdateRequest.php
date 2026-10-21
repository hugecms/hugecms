<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingAssistRecord;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingAssistRecordUpdateRequest',
    required: [
        self::getId,
        self::getActivityId,
        self::getUserId,
        self::getHelperCount,
        self::getStatus,
        self::getFinishTime,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getActivityId, description: '助力活动ID', type: 'integer'),
        new OA\Property(property: self::getUserId, description: '发起人用户ID', type: 'integer'),
        new OA\Property(property: self::getHelperCount, description: '已助力人数,达required_count触发发奖', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:1=进行中,2=已完成发奖,3=已过期', type: 'integer'),
        new OA\Property(property: self::getFinishTime, description: '完成时间', type: 'string'),
    ]
)]
class MarketingAssistRecordUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getActivityId = 'activityId';

    public const string getUserId = 'userId';

    public const string getHelperCount = 'helperCount';

    public const string getStatus = 'status';

    public const string getFinishTime = 'finishTime';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getActivityId => 'required',
            self::getUserId => 'required',
            self::getHelperCount => 'required',
            self::getStatus => 'required',
            self::getFinishTime => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getActivityId.'.required' => '请设置助力活动ID',
            self::getUserId.'.required' => '请设置发起人用户ID',
            self::getHelperCount.'.required' => '请设置已助力人数,达required_count触发发奖',
            self::getStatus.'.required' => '请设置状态:1=进行中,2=已完成发奖,3=已过期',
            self::getFinishTime.'.required' => '请设置完成时间',
        ];
    }
}
