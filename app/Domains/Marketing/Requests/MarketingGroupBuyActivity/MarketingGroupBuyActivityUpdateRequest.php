<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingGroupBuyActivity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingGroupBuyActivityUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getSpuId,
        self::getGroupSize,
        self::getStartTime,
        self::getEndTime,
        self::getPerLimit,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getSpuId, description: '商品SPU ID', type: 'integer'),
        new OA\Property(property: self::getGroupSize, description: '成团人数', type: 'integer'),
        new OA\Property(property: self::getStartTime, description: '活动开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '活动结束时间', type: 'string'),
        new OA\Property(property: self::getPerLimit, description: '每人限购(整个活动期)', type: 'integer'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingGroupBuyActivityUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getSpuId = 'spuId';

    public const string getGroupSize = 'groupSize';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getPerLimit = 'perLimit';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getSpuId => 'required',
            self::getGroupSize => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getPerLimit => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置活动名称',
            self::getSpuId.'.required' => '请设置商品SPU ID',
            self::getGroupSize.'.required' => '请设置成团人数',
            self::getStartTime.'.required' => '请设置活动开始时间',
            self::getEndTime.'.required' => '请设置活动结束时间',
            self::getPerLimit.'.required' => '请设置每人限购(整个活动期)',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
