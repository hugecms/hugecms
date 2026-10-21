<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Requests\MarketingPromotion;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MarketingPromotionCreateRequest',
    required: [
        self::getName,
        self::getType,
        self::getRangeType,
        self::getStartTime,
        self::getEndTime,
        self::getStatus,
    ],
    properties: [
        new OA\Property(property: self::getName, description: '活动名称', type: 'string'),
        new OA\Property(property: self::getType, description: '类型:full_reduce=满减,discount=折扣', type: 'string'),
        new OA\Property(property: self::getRangeType, description: '适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品', type: 'string'),
        new OA\Property(property: self::getStartTime, description: '活动开始时间', type: 'string'),
        new OA\Property(property: self::getEndTime, description: '活动结束时间', type: 'string'),
        new OA\Property(property: self::getStatus, description: '状态:0=停用,1=启用', type: 'integer'),
    ]
)]
class MarketingPromotionCreateRequest extends FormRequest
{
    public const string getName = 'name';

    public const string getType = 'type';

    public const string getRangeType = 'rangeType';

    public const string getStartTime = 'startTime';

    public const string getEndTime = 'endTime';

    public const string getStatus = 'status';

    public function rules(): array
    {
        return [
            self::getName => 'required',
            self::getType => 'required',
            self::getRangeType => 'required',
            self::getStartTime => 'required',
            self::getEndTime => 'required',
            self::getStatus => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            self::getName.'.required' => '请设置活动名称',
            self::getType.'.required' => '请设置类型:full_reduce=满减,discount=折扣',
            self::getRangeType.'.required' => '请设置适用范围:all=全场,category=指定分类,brand=指定品牌,product=指定商品',
            self::getStartTime.'.required' => '请设置活动开始时间',
            self::getEndTime.'.required' => '请设置活动结束时间',
            self::getStatus.'.required' => '请设置状态:0=停用,1=启用',
        ];
    }
}
