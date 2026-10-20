<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingFlashSale;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingFlashSaleCreateRequest',
    required: [
        self::getName,
        self::getStartTime,
        self::getEndTime,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '结束时间', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingFlashSaleCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置活动名称',
            self::getStartTime.'.required' => '请设置开始时间',
            self::getEndTime.'.required' => '请设置结束时间',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
