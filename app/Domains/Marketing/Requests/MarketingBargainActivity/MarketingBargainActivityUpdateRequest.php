<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingBargainActivity;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingBargainActivityUpdateRequest',
    required: [
        self::getId,
        self::getName,
        self::getSpuId,
        self::getStartTime,
        self::getEndTime,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getId, description: 'ID', type: 'integer'),
        new OA\Property(property: self::getName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getSpuId, description: '商品SPU ID', type: 'integer'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingBargainActivityUpdateRequest extends FormRequest
{
    public const string getId = 'id';

    public const string getName = 'name';

    public const string getSpuId = 'spuId';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getId => 'required',
            self::getName => 'required',
            self::getSpuId => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getId.'.required' => '请设置ID',
            self::getName.'.required' => '请设置活动名称',
            self::getSpuId.'.required' => '请设置商品SPU ID',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
